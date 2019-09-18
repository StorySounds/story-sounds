<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\OpenApi(
     *   @OA\Info(
     *     title="Story Sounds API",
     *     description="REST Apis for Story Sounds",
     *     version="1.0"
     *   )
     * )
     */

    /**
     * @OA\Tag(
     *   name="Authentication",
     *   description="Story Sounds authentication endpoints"
     * )
     * */

    /**
     * @OA\Get(
     *   path="/check",
     *   tags={"Authentication"},
     *   summary="Sample route for api authentication",
     *   operationId="check",
     *   @OA\Response(response="200",
     *     description="Success"
     *   ),
     *   security={
     *       {
     *           "AuthToken": {}
     *       }
     *   }
     * )
     */


}
