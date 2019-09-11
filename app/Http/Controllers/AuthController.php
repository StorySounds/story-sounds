<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(["status" => 'user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(["status" => 'token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(["status" => 'token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }

    /**
     * @OA\Get(
     *   path="/auth/social/{provider}",
     *   tags={"Auth"},
     *   summary="Redirects the user to the Social authentication page",
     *   operationId="redirectToProvider",
     *   @OA\Parameter(name="provider",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="200",
     *     description="The provider login page"
     *   )
     * )
     */

    /**
     * Redirect the user to the Social authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * @OA\Get(
     *   path="/auth/social/{provider}/callback",
     *   tags={"Auth"},
     *   summary="Handle provider callback/Login or Register user",
     *   operationId="handleProviderCallback",
     *   @OA\Parameter(name="provider",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="200",
     *     description="The user details"
     *   )
     * )
     */

    /**
     * Obtain the user information from Social network.
     *
     * @return JsonResponse
     */
    public function handleProviderCallback($provider, JWTAuth $JWTAuth)
    {
        $providerUser = Socialite::driver($provider)->stateless()->user();
        $user = User::query()->firstOrNew(['email' => $providerUser->getEmail()]);

        if (!$user->exists) {
            $user = new User;
            $user->email = $providerUser->getEmail();
            $user->save();
        }

        $token = $JWTAuth->fromUser($user);
        
        return response()->json([
            'token' => $token
        ]);
    }
}