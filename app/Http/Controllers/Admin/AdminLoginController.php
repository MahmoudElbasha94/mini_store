<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showAdminLoginForm() {
        return view('admin.auth.login');
    }


    public function adminLogin(Request $request) {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required','min:8'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();  // Regenerate session ID for security
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'admin_login_error' => 'invalid email or password.',
        ]);
    }

    public function adminLogout(Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();  // Regenerate the CSRF token value.
        return redirect()->route('admin.login');
    }
}
