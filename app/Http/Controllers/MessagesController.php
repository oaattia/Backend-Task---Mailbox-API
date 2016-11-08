<?php

namespace App\Http\Controllers;

use App\Message;
use App\Presenters\MessagesPresenter;
use App\Presenters\Presenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessagesController extends ApiController
{
    /**
     * @var Presenter
     */
    private $presenter;


    /**
     * @var Request
     */
    private $request;

    /**
     * MessagesController constructor.
     *
     * @param MessagesPresenter $presenter
     * @param Request           $request
     */
    public function __construct(MessagesPresenter $presenter, Request $request)
    {
        $this->presenter = $presenter;
        $this->request = $request;
    }

    /**
     * Get all list for the messages
     * and pass the status if you want filter by either archived or read parameters.
     *
     * @return mixed
     */
    public function index()
    {
        $messages = new Message();

        if ($status = $this->request->input('status')) {
            $validator = Validator::make(['status' => $status], [
                'status' => 'in:archived,read',
            ], ['in' => 'Status should be archived or read']);

            if ($validator->fails()) {
                return $this->respondUnprocessableEntityError($validator->getMessageBag());
            }

            $messages = $messages->where($status, 1);
        }

        $messages = $messages->paginate($this->request->input('limit') ?: 3);

        if (!$messages->count()) {
            return $this->respondNotFound();
        }

        return $this->respond($this->presenter->collections($messages->toArray()));
    }

    /**
     * Display specific message by Id.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'exists:messages,id',
        ]);

        if ($validator->fails()) {
            return $this->respondUnprocessableEntityError($validator->getMessageBag());
        }

        try {
            $message = Message::findOrFail($id);

            return $this->respond($this->presenter->transform($message->toArray()));
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
    }

    /**
     * Assign message as archive in database.
     *
     * @param $id
     *
     * @return mixed
     */
    public function postArchive($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->update(['archived' => 1]);

            return $this->respond($this->presenter->transform($message->toArray()));
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
    }

    /**
     * Assign Message as read in database.
     *
     * @param $id
     *
     * @return mixed
     */
    public function postRead($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->update(['read' => 1]);

            return $this->respond($this->presenter->transform($message->toArray()));
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
    }
}
