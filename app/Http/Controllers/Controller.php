<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\OpenApi(
     *   @OA\Info(
     *     title="Story Sounds API",
     *     description="REST Apis",
     *     version="1.0"
     *   )
     * )
     */

    /**
	 * @OA\Get(
	 *     path="/version",
	 *     description="Lumen version example route",
	 *     @OA\Response(response="default", description="Version page")
	 * )
	 */
}
