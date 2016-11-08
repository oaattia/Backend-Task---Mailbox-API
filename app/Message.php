<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Cast the variable to it's types.
     *
     * @var array
     */
    protected $casts = [
        'uid'       => 'integer',
        'sender'    => 'string',
        'subject'   => 'string',
        'message'   => 'string',
        'read'      => 'integer',
        'archived'  => 'integer',
        'time_sent' => 'timestamp',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * The timestamp for the model.
     *
     * @var bool
     */
    public $timestamps = false;
}
