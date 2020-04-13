<?php

namespace EPink\Blog\Http\Controllers;

use Carbon\Carbon;
use EPink\Blog\Models\Author;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function login(Request $request) 
  {
    try {
      $validated = $request->validate([
        'email' => 'required',
        'password' => 'required'
      ]);

      $user = Author::where('email', $request->email)->where('is_disabled', false)->first();
      
      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Credentials not found'], 401);
      } else {
        $tokenResult = $user->createToken('test', ['admin']);

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $user->last_login = Carbon::now();
        $user->save();

        return response()->json([
          'user' => $user,
          'access_token' => "Bearer " . $tokenResult->accessToken
        ]);
      }
    }
    catch (\Exception $e) {
      return response()->json(['message' => 'Unexpected server error']);
    }
  }
}