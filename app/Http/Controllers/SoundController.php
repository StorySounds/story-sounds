<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sound;

class SoundController extends Controller
{
    /**
     * @OA\Get(
     *   path="/sounds/{id}",
     *   tags={"Sound & Trigger"},
     *   summary="Read a sound by id",
     *   description="Return a sound corresponding to an id",
     *   operationId="read",
     *   @OA\Parameter(
     *       description="ID of sound to fetch",
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
    	$sound = Sound::where('id', $id)->with('trigger')->first();
    	if($sound){
    		return response()->json([
	            'status' => "success",
	            'data' => $sound
	        ]);
    	}
    	return response()->json([
            'status' => "fail",
            'error' => "Resource not found"
        ], 404);
    }

    /**
     * @OA\Get(
     *   path="/sounds/{id}/trigger",
     *   tags={"Sound & Trigger"},
     *   summary="Get trigger related to a sound",
     *   description="Return the trigger related to a sound",
     *   operationId="read",
     *   @OA\Parameter(
     *       description="ID of sound",
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
    public function getTrigger($id)
    {
    	$sound = Sound::where('id', $id)->first();
    	if($sound){
    		return response()->json([
	            'status' => "success",
	            'data' => $sound->trigger
	        ]);
    	}
    	return response()->json([
            'status' => "fail",
            'error' => "Resource not found"
        ], 404);
    }
}
