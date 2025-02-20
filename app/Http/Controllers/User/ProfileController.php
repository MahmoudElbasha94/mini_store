<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('user.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $profileValidationData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'name' => $profileValidationData['name'],
            'email' => $profileValidationData['email'],
            'password' => $request->filled('password') ? Hash::make($profileValidationData['password']) : $user->password,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
