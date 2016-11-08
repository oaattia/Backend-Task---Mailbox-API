<?php

namespace App\Presenters;

use Carbon\Carbon;

class MessagesPresenter extends Presenter
{

    /**
     *
     * @param $item
     *
     * @return mixed
     */
    public function transform($item)
    {
        return [
            'uid'       => $item['uid'],
            'sender'    => $item['sender'],
            'subject'   => $item['subject'],
            'message'   => $item['message'],
            'read'      => $item['read'],
            'archived'  => $item['archived'],
            'time_sent' => Carbon::createFromTimestamp($item['time_sent'])->toDateTimeString(),
        ];
    }
}