<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;

class StoryController extends Controller
{
    public function list()
    {
    	$stories = Story::with("sounds")->get();
    	return response()->json([
            'status' => "Sucess",
            'data' => $stories
        ]);
    }

    public function read($id)
    {
    	$story = Story::where('id', $id)->with('sounds')->first();
    	if($story){
    		return response()->json([
	            'status' => "Sucess",
	            'data' => $story
	        ]);
    	}
    	return response()->json([
            'status' => "Fail",
            'error' => "Resource not found"
        ], 404);
    }

    public function listSounds($id)
    {
    	$story = Story::where('id', $id)->first();
    	$sounds = $story->sounds;
    	return response()->json([
            'status' => "Sucess",
            'data' => $sounds
        ]);
    }
}
