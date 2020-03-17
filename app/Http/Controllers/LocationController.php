<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Location;
use App\Repositories\LocationRepository;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $location = $this->model->show($id);
        return $location ? response()->json($location) : response('null');
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

        if ($location['userId'] === Auth('api')->id()) {
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
