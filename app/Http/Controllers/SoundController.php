<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sound;

class SoundController extends Controller
{
    public function read($id)
    {
    	$sound = Sound::where('id', $id)->with('trigger')->first();
    	if($sound){
    		return response()->json([
	            'status' => "Sucess",
	            'data' => $sound
	        ]);
    	}
    	return response()->json([
            'status' => "Fail",
            'error' => "Resource not found"
        ], 404);
    }

    public function getTrigger($id)
    {
    	$sound = Sound::where('id', $id)->first();
    	if($sound){
    		return response()->json([
	            'status' => "Sucess",
	            'data' => $sound->trigger
	        ]);
    	}
    	return response()->json([
            'status' => "Fail",
            'error' => "Resource not found"
        ], 404);
    }
}
