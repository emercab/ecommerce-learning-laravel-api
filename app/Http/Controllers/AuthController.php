<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\VerifiedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', [
      'except' => [
        'login',
        'loginEcommerce',
        'register',
        'verifyEmail',
      ]
    ]);
  }


  /**
   * Register a User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function register()
  {
    $validator = Validator::make(request()->all(), [
      'name' => 'required',
      'address' => 'required',
      'phone' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }

    $user = new User;
    $user->name = request()->name;
    $user->address = request()->address;
    $user->phone = request()->phone;
    $user->email = request()->email;
    $user->type_user = 2;
    $user->password = bcrypt(request()->password);
    $user->unique_code = uniqid();
    $user->save();

    // Send email verification
    Mail::to($user->email)->send(new VerifiedMail($user));

    return response()->json($user, 201);
  }


  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login()
  {
    if (!$token = auth('api')->attempt([
      'email' => request()->email,
      'password' => request()->password,
      'type_user' => 1,
    ])) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }

  public function loginEcommerce()
  {
    if (!$token = auth('api')->attempt([
      'email' => request()->email,
      'password' => request()->password,
      'type_user' => 2,
    ])) {
      return response()->json(
        [
          'error' => 'Unauthorized',
          'message' => 'Email or password is incorrect.',
          'code' => 1
        ],
        401
      );
    }

    if (!auth('api')->user()->email_verified_at) {
      return response()->json(
        [
          'error' => 'Email not verified',
          'message' => 'Please verify your email first.',
          'code' => 2
        ],
        401
      );
    }

    return $this->respondWithToken($token);
  }

  /**
   * Verify email.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function verifyEmail(Request $request)
  {
    $user = User::where('unique_code', $request->code)->first();

    if (!$user) {
      return response()->json(
        [
          'error' => 'Not Found',
          'message' => 'Error verifying email',
          'code' => 1,
        ],
        404
      );
    }

    $user->email_verified_at = now();
    $user->unique_code = null;
    $user->save();

    return response()->json(
      [
        'verified' => true,
        'message' => 'Email verified successfully. Please login.',
        'unique_code' => $request->code,
      ],
      200
    );
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(auth('api')->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth('api')->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return $this->respondWithToken(auth('api')->refresh());
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
      'accessToken' => $token,
      'tokenType' => 'bearer',
      'expiresIn' => auth('api')->factory()->getTTL() * 60,
      'user' => [
        'name' => auth('api')->user()->name,
        'email' => auth('api')->user()->email,
      ],
    ]);
  }
}
