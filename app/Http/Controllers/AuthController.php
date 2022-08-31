<?php

namespace App\Http\Controllers;

use App\Models\MhAdmin;
use App\Models\User;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    use RespondsWithHttpStatus;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Authenticating using the md5 hashing
        $user = MhAdmin::where([
            'kode' => $request->kode,
            'sandi' => md5($request->sandi)
        ])->first();

        if ($user) {
            $token = $this->guard()->login($user);
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Invalid Login Details'], 401);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function me()
    {
        // return response()->json($this->guard()->user(), 200);

        return $this->success('OK', $this->guard()->user(), 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
