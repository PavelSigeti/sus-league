<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = auth()->user()->only(['name', 'surname', 'patronymic', 'id', 'role', 'email']);
            return [
                'status' => 'success',
                'user' => $user,
            ];
        }

        return abort(401, ['status' => 'error', 'message' => 'Unauthorized']);

    }

}
