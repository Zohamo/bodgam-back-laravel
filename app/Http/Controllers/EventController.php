<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\EventRequest;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $model;
    private $queryAllOptions = [];

    /**
     * Construct an instance of EventController
     *
     * @param  \App\Event $event
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->model = new EventRepository($event);
        $this->queryAllOptions = [
            'past' => false,
            'userId' => null,
            'isAdmin' => false
        ];
    }

    /**
     * Display a listing of the Events to come.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->model->allWithOptions($this->queryAllOptions));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allArchive()
    {
        $this->queryAllOptions['past'] = true;
        return response()->json($this->model->allWithOptions($this->queryAllOptions));
    }


    /**
     * Display a listing of the resource of a specific User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAll(int $id)
    {
        $this->queryAllOptions['userId'] = $id;
        $this->queryAllOptions['isAdmin'] = Auth('api')->id() == $id;
        return response()->json($this->model->allWithOptions($this->queryAllOptions));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $event = $this->model->show($id);
        return $event ? response()->json($event) : response('null');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\EventRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EventRequest $request)
    {
        $input = $request->only($this->model->getModel()->fillable);
        $input['userId'] = Auth('api')->id();

        return response()->json($this->model->create($input));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\EventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EventRequest $request, int $id)
    {
        $event = $this->model->show($id);

        if ($event['userId'] === Auth('api')->id()) {
            return response()->json(
                $this->model->update(
                    $request->only(
                        $this->model->getModel()->fillable
                    ),
                    $id
                )
            );
        } else {
            return response()->json(
                config('messages.401'),
                401
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        return Auth('api')->id() == $id
            ? response()->json($this->model->delete($id))
            : response()->json(
                config('messages.401'),
                401
            );
    }
}
