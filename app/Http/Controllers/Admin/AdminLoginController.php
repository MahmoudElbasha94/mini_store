<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    // Display the admin login form
    public function showAdminLoginForm()
    {
        return view('admin.auth.login');
    }

// Handle admin login request
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',    // Validate email and password
            'password' => 'required|min:8',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();  // Regenerate session for security
            return redirect()->intended(route('admin.dashboard')); // Redirect to dashboard
        }

        return back()->withErrors(['admin_login_error' => 'Invalid email or password.']); // Error on failure
    }

// Handle admin logout request
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout(); // Log out admin
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token
        return redirect()->route('admin.login'); // Redirect to login page
    }
}
