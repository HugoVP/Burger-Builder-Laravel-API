<?php

namespace App\Http\Controllers;

use JWTAuth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller {
  protected static $rules = [
    'email' => 'required|email|unique:users', /* Alphanumeric (no numbers at te beggining) */
    'password' => 'required|regex:/^(?!.*\s).{6,255}$/', /* Only no-space characters */
  ];

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct() {
    // $this->middleware('tymon.jwt.auth', ['except' => ['signup', 'login']]);
    $this->middleware('auth:api', ['except' => ['signup', 'login']]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function signup(Request $request) {
    $credentials = request(['email', 'password']);
    $validator = Validator::make($credentials, self::$rules);
    
    if ($validator->fails()) {
      return response()->json(['error' => ['message' => $validator->errors()->first()]], 400);
    }

    $user = new User;
    $user->email = $credentials['email'];
    $user->password = $credentials['password'];

    if (!$user->save()) {
      return response()->json(['error' => ['message' => 'user_not_saved']], 500);
    }

    if (!$token = auth()->login($user)) {
      return response()->json(['error' => ['message' => 'Unauthorized']], 401);
    }

    $response = [
      'idToken' => $token,
      'email' => $user->email,
      'expiresIn' => 3600,
      'localId' => $user->id,
    ];

    return response()->json($response, 200);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login() {
    $credentials = request(['email', 'password']);

    if (!$token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    try {
      $user = auth()->userOrFail();
    } catch (UserNotDefinedException $e) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $response = [
      'idToken' => $token,
      'email' => $user->email,
      'expiresIn' => 3600,
      'localId' => $user->id,
    ];

    return response()->json($response, 200);

    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me() {
    return response()->json(auth()->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout() {
    auth()->logout();
    
    // try {
    //   JWTAuth::parseToken()->invalidate();
    // } catch (TokenExpiredException $e) {
    //     return response()->json(['error' => 'token_expired'], $e->getStatusCode());
    // } catch (TokenInvalidException $e) {
    //     return response()->json(['error' => 'token_invalid'], 401);
    // } catch (JWTException $e) {
    //     return response()->json(['error' => 'token_not_provided'], $e->getStatusCode());
    // }

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh() {
    return $this->respondWithToken(auth()->refresh());
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token) {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ]);
  }
}
