<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    /**
     * Return a boolean to verify the Front/Back End connexion.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ping()
    {
        return response()->json(1);
    }

    /**
     * Return the message sent by the Front End.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function push(Request $request)
    {
        // event(new AdminEvent($message));
        return response()->json('hello back');
    }
}
