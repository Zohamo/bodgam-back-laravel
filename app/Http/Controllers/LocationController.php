<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\LocationRequest;
use App\Location;
use App\Repositories\EventRepository;
use App\Repositories\LocationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    protected $model;
    private $queryAllOptions = [];

    /**
     * Construct an instance of LocationController
     *
     * @param  \App\Location $location
     * @return void
     */
    public function __construct(Location $location)
    {
        $this->model = new LocationRepository($location);
        $this->queryAllOptions = [
            'userId' => null,
            'isAdmin' => false
        ];
    }

    /**
     * Send a 401 error if the User is not authorized.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    private function checkIfUserIsAuthorized(int $userId)
    {
        if ($userId != Auth('api')->id()) {
            return response()->json(config('messages.401'), 401);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
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
     * Display a listing of the Events related to a specific Location.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function allEvents(int $id)
    {
        $location = $this->model->with('events')->find($id);

        $this->checkIfUserIsAuthorized($location['userId']);

        return response()->json($location['events']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $location = $this->model->with('events.players')->find($id);
        $response = $location ? response()->json($location) : response('null');

        if ($location['userId'] == Auth('api')->id()) {
            return $response;
        }

        foreach ($location['events'] as $event) {
            foreach ($event['players'] as $player) {
                if ($player['id'] == Auth('api')->id()) {
                    return $response;
                }
            }
        }

        return response()->json(config('messages.401'), 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\LocationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LocationRequest $request)
    {
        $input = $request->only($this->model->getModel()->fillable);
        $input['userId'] = Auth('api')->id();

        return response()->json($this->model->create($input));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\LocationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LocationRequest $request, int $id)
    {
        $location = $this->model->show($id);

        $this->checkIfUserIsAuthorized($location['userId']);

        return response()->json(
            $this->model->update(
                $request->only(
                    $this->model->getModel()->fillable
                ),
                $id
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $location = $this->model->with('events')->find($id);

        $this->checkIfUserIsAuthorized($location['userId']);

        foreach ($location['events'] as $event) {
            if ($event['startDatetime'] > Carbon::today()) {
                return response()->json(
                    config('This Location is in use in an Event to come.'),
                    403
                );
            }
        }

        return response()->json($this->model->delete($id));
    }
}
