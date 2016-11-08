<?php

$app->group(['prefix' => 'api'], function () use ($app) {
    $app->post('register', 'AuthController@postRegister');
    $app->group(['middleware' => 'auth'], function () use ($app) {
        $app->get('messages', 'MessagesController@index');
        $app->get('messages/{id}', 'MessagesController@show');
        $app->post('messages/archive/{id}', 'MessagesController@postArchive');
        $app->post('messages/read/{id}', 'MessagesController@postRead');
    });
});