<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use App\EmailLogin;
use Illuminate\Support\Facades\Mail;
use App\User;

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

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Logs a user in",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         description="User credentials for authentication",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User's email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="User's password"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */

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

        return response()->json([
            'status' => "success",
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @OA\Get(
     *   path="/auth/social/{provider}",
     *   tags={"Authentication"},
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
     *   tags={"Authentication"},
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
            'status' => "success",
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }

    public function passwordlessLogin(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $emailLogin = EmailLogin::createForEmail($request->input('email'));

        $url = route('auth.email-authenticate', [
            'token' => $emailLogin->token
        ]);

        Mail::send('emails.email-login', ['url' => $url], function ($m) use ($request) {
            $m->from('noreply@storysound.com', 'StorySound');
            $m->to($request->input('email'))->subject('StorySound login');
        });

        return response()->json([
            "status" => "success",
            "message" => "Login email sent. Go check your email."
        ]);
    }

    public function authenticateEmail($token, JWTAuth $JWTAuth)
    {
        $emailLogin = EmailLogin::validFromToken($token);

        $user = User::query()->firstOrNew(['email' => $emailLogin->email]);

        if (!$user->exists) {
            $user = new User;
            $user->email = $emailLogin->email;
            $user->save();
        }

        $token = $JWTAuth->fromUser($emailLogin->user);

        return response()->json([
            'status' => "success",
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }
}