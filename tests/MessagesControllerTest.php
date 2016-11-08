<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class MessagesControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(\App\User::class, 1)->create();
    }

    /** @test */
    public function it_should_list_all_messages_if_authenticated()
    {
        $messages = factory(\App\Message::class, 1)->create([
            'uid'       => 1,
            'archived'  => 0,
            'read'      => 0,
            'message'   => 'foo test',
            'sender'    => 'bar',
            'subject'   => 'baz',
            'time_sent' => $timestamp = time(),
        ]);

        $response = $this->json('GET', 'api/messages', ['api_token' => $this->user->api_token]);

        $response->seeJsonEquals([
            "current_page"  => 1,
            "from"          => 1,
            "last_page"     => 1,
            "next_page_url" => null,
            "per_page"      => 3,
            "prev_page_url" => null,
            "to"            => 1,
            "total"         => 1,
            "data"          => [
                [
                    'archived'  => $messages->archived,
                    'read'      => $messages->read,
                    'uid'       => $messages->uid,
                    'message'   => $messages->message,
                    'sender'    => $messages->sender,
                    'subject'   => $messages->subject,
                    'time_sent' => \Carbon\Carbon::createFromTimestamp($timestamp)->toDateTimeString(),
                ],
            ],
        ]);

        $response->assertResponseOk();

    }

    /** @test */
    public function it_shouldnt_list_all_messages_if_not_authenticated()
    {
        $response = $this->json('GET', 'api/messages');
        $response->seeJsonEquals([
            "error" => [
                "message"     => "Unauthorized",
                "status_code" => 401,
            ],
        ]);

        $response->assertResponseStatus(401);
    }

    /** @test */
    public function it_should_filter_by_status_if_there_status_parameter()
    {
        $messages = factory(\App\Message::class, 1)->create([
            'uid'       => 1,
            'archived'  => 1,
            'read'      => 0,
            'message'   => 'foo test',
            'sender'    => 'bar',
            'subject'   => 'baz',
            'time_sent' => $timestamp = time(),
        ]);

        $anotherMessage = factory(\App\Message::class, 1)->create([
            'uid'       => 2,
            'archived'  => 0,
            'read'      => 0,
            'message'   => 'foo test',
            'sender'    => 'bar',
            'subject'   => 'baz',
            'time_sent' => $timestamp = time(),
        ]);

        $response = $this->json('GET', 'api/messages', ['api_token' => $this->user->api_token, 'status' => 'archived']);

        $response->seeJsonEquals([
            "current_page"  => 1,
            "from"          => 1,
            "last_page"     => 1,
            "next_page_url" => null,
            "per_page"      => 3,
            "prev_page_url" => null,
            "to"            => 1,
            "total"         => 1,
            "data"          => [
                [
                    'archived'  => $messages->archived,
                    'read'      => $messages->read,
                    'uid'       => $messages->uid,
                    'message'   => $messages->message,
                    'sender'    => $messages->sender,
                    'subject'   => $messages->subject,
                    'time_sent' => \Carbon\Carbon::createFromTimestamp($timestamp)->toDateTimeString(),
                ],
            ],
        ]);


    }


    /** @test */
    public function it_should_change_status_to_true_either_it_is_read_or_archived()
    {
        $message = factory(\App\Message::class, 1)->create([
            'uid'       => 1,
            'archived'  => 0,
            'read'      => 0,
            'message'   => 'foo test',
            'sender'    => 'bar',
            'subject'   => 'baz',
            'time_sent' => $timestamp = time(),
        ]);

        $response = $this->json('POST', 'api/messages/archive/' . $message->id, ['api_token' => $this->user->api_token]);

        $response->seeJsonEquals([
                'archived'  => 1,
                'read'      => $message->read,
                'uid'       => $message->uid,
                'message'   => $message->message,
                'sender'    => $message->sender,
                'subject'   => $message->subject,
                'time_sent' => \Carbon\Carbon::createFromTimestamp($timestamp)->toDateTimeString(),
        ]);

        $anotherResponse = $this->json('POST', 'api/messages/read/' . $message->id, ['api_token' => $this->user->api_token]);

        $anotherResponse->seeJsonEquals([
                'archived'  => 1,
                'read'      => 1,
                'uid'       => $message->uid,
                'message'   => $message->message,
                'sender'    => $message->sender,
                'subject'   => $message->subject,
                'time_sent' => \Carbon\Carbon::createFromTimestamp($timestamp)->toDateTimeString(),
        ]);


        $this->assertResponseOk();
    }

    /** @test */
    public function it_should_show_single_message_with_read_and_archive()
    {
        $message = factory(\App\Message::class, 1)->create([
            'uid'       => 1,
            'archived'  => 0,
            'read'      => 0,
            'message'   => 'foo test',
            'sender'    => 'bar',
            'subject'   => 'baz',
            'time_sent' => $timestamp = time(),
        ]);

        $response = $this->json('GET', 'api/messages/' . $message->id, ['api_token' => $this->user->api_token]);


        $response->seeJsonEquals([
            'archived'  => $message->archived,
            'read'      => $message->read,
            'uid'       => $message->uid,
            'message'   => $message->message,
            'sender'    => $message->sender,
            'subject'   => $message->subject,
            'time_sent' => \Carbon\Carbon::createFromTimestamp($timestamp)->toDateTimeString(),
        ]);


        $this->assertResponseOk();

    }


}
