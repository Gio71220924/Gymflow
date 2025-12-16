<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GymClass;

class GymClassAPIController extends Controller
{
    //
    public function getClass()
    {
        $classes = GymClass::orderby('title', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Retrieved gym classes successfully', 
            'data' => $classes,
        ],200);
    }
}
