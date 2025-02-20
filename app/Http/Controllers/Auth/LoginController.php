<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('user.auth.login');
    }


    public function login(Request $request) {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required','min:8'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();  // Regenerate session ID for security
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login_error' => 'invalid email or password.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();  // Regenerate the CSRF token value.
        return redirect()->route('home');
    }
}
