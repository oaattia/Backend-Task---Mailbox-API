<?php

$app->group(['prefix' => 'api'], function () use ($app) {
    // Register a new user and generate a new token
    $app->post('register', 'AuthController@postRegister');

    // This group need the user to be authenticated
    $app->group(['middleware' => 'auth'], function () use ($app) {
        $app->get('messages', 'MessagesController@index');
        $app->get('messages/{id}', 'MessagesController@show');
        $app->post('messages/archive/{id}', 'MessagesController@postArchive');
        $app->post('messages/read/{id}', 'MessagesController@postRead');
    });
});
