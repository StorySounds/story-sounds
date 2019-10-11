<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;

class StoryController extends Controller
{
    /**
     * @OA\Get(
     *   path="/stories",
     *   tags={"Story"},
     *   summary="List all stories",
     *   description="Returns a list of all the stories",
     *   operationId="list",
     *   @OA\Response(
     *      response=200,
     *      description="Successful operation!"
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthorized! Authentication token missing or invalid."
     *   ),
     *   security={
     *       {
     *           "AuthToken": {}
     *       }
     *   }
     * )
     */
    public function list()
    {
    	$stories = Story::with("sound_triggers.sound")->get();
    	return response()->json([
            'status' => "success",
            'data' => $stories
        ]);
    }

    /**
     * @OA\Get(
     *   path="/stories/{id}",
     *   tags={"Story"},
     *   summary="Read a story by id",
     *   description="Return a story corresponding to an id",
     *   operationId="read",
     *   @OA\Parameter(
     *       description="ID of story to fetch",
     *       in="path",
     *       name="id",
     *       required=true,
     *       @OA\Schema(
     *           type="integer",
     *           format="int64",
     *       )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Successful operation!"
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthorized! Authentication token missing or invalid."
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *   security={
     *       {
     *           "AuthToken": {}
     *       }
     *   }
     * )
     */
    public function read($id)
    {
    	$story = Story::where('id', $id)->with('sound_triggers.sound')->first();
    	if($story){
    		return response()->json([
	            'status' => "success",
	            'data' => $story
	        ]);
    	}
    	return response()->json([
            'status' => "fail",
            'error' => "Resource not found"
        ], 404);
    }

    /**
     * @OA\Get(
     *   path="/stories/{id}/sound_triggers",
     *   tags={"Story"},
     *   summary="List all sound triggers belonging to a story",
     *   description="Returns a list of all the sound triggers related to a story",
     *   operationId="listSoundTriggers",
     *   @OA\Parameter(
     *       description="ID of story",
     *       in="path",
     *       name="id",
     *       required=true,
     *       @OA\Schema(
     *           type="integer",
     *           format="int64",
     *       )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Successful operation!"
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthorized! Authentication token missing or invalid."
     *   ),
     *   security={
     *       {
     *           "AuthToken": {}
     *       }
     *   }
     * )
     */
    public function listSoundTriggers($id)
    {
    	$story = Story::where('id', $id)->with('sound_triggers.sound')->first();
    	$sound_triggers = $story['sound_triggers'];
    	return response()->json([
            'status' => "success",
            'data' => $sound_triggers
        ]);
    }
}
