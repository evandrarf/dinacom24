<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            return $this->messageSuccess("Login berhasil");
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return $this->messageSuccess("Logout berhasil");
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }
}
