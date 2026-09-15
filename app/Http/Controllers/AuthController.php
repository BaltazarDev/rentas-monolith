<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $target = Auth::user()->isOperator() ? route('houses.index') : '/dashboard';
            return redirect()->intended($target);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Default remember to true for PWA persistence
        $remember = $request->has('remember') ? $request->boolean('remember') : true;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            try {
                \App\Models\AccessLog::create([
                    'user_id' => Auth::id(),
                    'email' => $request->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'success',
                ]);
            } catch (\Throwable $e) {
                // Ignore logging failure to not block user
            }

            $target = Auth::user()->isOperator() ? route('houses.index') : '/dashboard';
            return redirect()->intended($target);
        }

        try {
            $matchingUser = \App\Models\User::where('email', $request->email)->first();
            \App\Models\AccessLog::create([
                'user_id' => $matchingUser ? $matchingUser->id : null,
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
            ]);
        } catch (\Throwable $e) {
            // Ignore logging failure
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
