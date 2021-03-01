<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventSubscription;
use App\Http\Requests\EventSubscriptionRequest;
use App\Repositories\EventRepository;
use App\Repositories\EventSubscriptionRepository;

class EventSubscriptionController extends Controller
{
    protected $model;
    private $queryAllOptions = [];

    /**
     * Construct an instance of EventSubscription
     *
     * @param  \App\EventSubscription $eventSubscription
     * @return void
     */
    public function __construct(EventSubscription $eventSubscription)
    {
        $this->model = new EventSubscriptionRepository($eventSubscription);
        $this->queryAllOptions = [
            'eventId' => null,
            'userId' => null
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $userId)
    {
        $this->queryAllOptions['userId'] = $userId;
        return response()->json($this->model->allWithOptions($this->queryAllOptions));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $eventId
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $eventId, int $userId)
    {
        $this->queryAllOptions['eventId'] = $eventId;
        $this->queryAllOptions['userId'] = $userId;
        return response()->json($this->model->showWithOptions($this->queryAllOptions));
    }

    /**
     * Update the user's subscription to an event.
     *
     * @param  \App\Http\Requests\EventSubscriptionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EventSubscriptionRequest $request, int $id)
    {
        $input = $request->only($this->model->getModel()->fillable);

        $event = new EventRepository(new Event());
        $event = $event->show($input['eventId']);

        switch (Auth('api')->id()) {
            case $input['userId']:
                // User is a Subscriptor
                $input['isAccepted'] = null;
                break;
            case $event->userId:
                // User is the Host/Administrator
                break;
            default:
                // User is unauthorized
                return response()->json(
                    config('messages.401'),
                    401
                );
        }

        return response()->json($this->model->update($input, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $eventId
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $eventId, int $userId)
    {
        if (Auth('api')->id() != $userId) {
            return response()->json(config('messages.401'), 401);
        }
        $deleteResult = $this->model->deleteWithOptions([
            "eventId" => $eventId,
            "userId" => $userId
        ]);
        if ($deleteResult) {
            return response()->json($deleteResult);
        }
    }
}
