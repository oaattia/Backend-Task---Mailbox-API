<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * AuthController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Register a new user and create a token
     *
     * @return mixed
     */
    public function postRegister()
    {
        $validator = Validator::make($this->request->all(), [
            'name'  => 'required',
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return $this->respondUnprocessableEntityError($validator->getMessageBag());
        }

        $token = Hash::make(str_random(22));

        User::create([
            'name'      => $this->request->input('name'),
            'email'     => $this->request->input('email'),
            'api_token' => $token,
        ]);

        return $this->respondCreated([
            'api_token' => $token,
        ]);
    }


}
