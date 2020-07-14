<?php

namespace App\Http\Controllers\Admin;

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
}
