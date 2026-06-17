<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة. / Invalid credentials.'
            ], 401);
        }

        // Generate custom token
        $token = Str::random(60);
        $user->forceFill([
            'api_token' => $token,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح. / Login successful.',
            'token'   => $token,
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->forceFill([
                'api_token' => null,
            ])->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح. / Logged out successfully.'
        ], 200);
    }
}
