<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "validation error",
                "errors" => $validator->errors()->all(),

            ], 401);
        } else {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
            ]);

            return response()->json([
                "status" => true,
                "message" => "User created successfully",
                "users" => $user,

            ], 200);
        }
    }
            catch(ValidationException $e) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage()
                ], 400);
            }

    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Authentication Failed",
                "errors" => $validator->errors()->all(),
            ], 401);
        }
        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            return response()->json([
                "status" => true,
                "message" => "User Login Successfully",
                "token" => $user->createToken("auth_token")->plainTextToken,
                "token_type" => "Bearer",
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => " Email & password not match",

            ], 401);
        }
    }

    public function logout(Request $request) {
        // $request->user()->tokens()->delete(); // user all tokens delete
        $request->user()->currentAccessToken()->delete(); // user current token delete

        return response()->json([
            "status" => true,
            "message" => "Logout Successful",

        ]);
    }
}
