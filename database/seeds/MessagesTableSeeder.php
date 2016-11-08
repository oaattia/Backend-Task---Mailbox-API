<?php

use App\Message;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $object = $this->import(base_path('messages_sample.json'));

        foreach ($object->messages as $message) {
            Message::create([
                'uid'       => $message->uid,
                'sender'    => $message->sender,
                'subject'   => $message->subject,
                'message'   => $message->message,
                'time_sent' => $message->time_sent,
            ]);
        }
    }

    /**
     * @return mixed
     */
    private function import($file)
    {
        $json = file_get_contents($file);
        $object = json_decode($json);

        return $object;
    }
}
