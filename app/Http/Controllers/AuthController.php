<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
 * @api {post} /api/login Bejelentkezes
 * @apiName Login
 * @apiGroup Hitelesites
 * @apiBody {String} email Email-cim.
 * @apiBody {String} password Jelszo.
 * @apiSuccess {String} token A kapott Bearer token.
 * @apiError (401) Unauthorized Hibas email vagy jelszo.
 */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Hibás e-mail vagy jelszó.'
            ], 401);
        }

        $token = $user->createToken('access')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
    /**
 * @api {post} /api/logout Kijelentkezes
 * @apiName Logout
 * @apiGroup Hitelesites
 * @apiHeader {String} Authorization Bearer token.
 * @apiDescription Torli az aktualisan hasznalt tokent.
 */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sikeres kijelentkezés.'
        ]);
    }
}