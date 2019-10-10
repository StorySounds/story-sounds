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
     *      description="Authentication token missing. Unauthorized!"
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
    	$stories = Story::with("sounds")->get();
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
     *      description="Authentication token missing. Unauthorized!"
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
    	$story = Story::where('id', $id)->with('sounds')->first();
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
     *   path="/stories/{id}/sounds",
     *   tags={"Story"},
     *   summary="List all sounds belonging to a story",
     *   description="Returns a list of all the sounds related to a story",
     *   operationId="list",
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
     *      description="Authentication token missing. Unauthorized!"
     *   ),
     *   security={
     *       {
     *           "AuthToken": {}
     *       }
     *   }
     * )
     */
    public function listSounds($id)
    {
    	$story = Story::where('id', $id)->first();
    	$sounds = $story->sounds;
    	return response()->json([
            'status' => "success",
            'data' => $sounds
        ]);
    }
}
