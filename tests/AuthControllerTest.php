<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_should_fail_when_create_user_without_name_or_email()
    {
        $request = $this->post('api/register');

        $request->seeJson([
           'status_code' => 422,
           'email' => ['The email field is required.'],
           'name'  => ['The name field is required.']
        ]);

        $request->assertResponseStatus(422);            // UNPROCESSED ENTITY

        $request = $this->post('api/register', ['name' => 'foo']);

        $request->seeJson([
            'status_code' => 422,
            'email' => ['The email field is required.'],
        ]);

        $request->assertResponseStatus(422);
    }

    /** @test */
    public function it_should_success_and_return_token_if_there_is_name_and_email()
    {
        $request = $this->post('api/register', ['name' => 'foo', 'email' => 'test@test.com']);

        $request->seeJsonStructure([
            'message' => [
                'api_token'
            ]
        ]);

        $request->assertResponseStatus(201);
    }

}
