<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show registration form
    public function showRegistrationForm(Request $request)
    {
        // REQUIREMENT 3: If already logged in, cannot go back to auth pages
        if ($request->session()->has('user_id')) {
            return redirect()->route('eloquent.index');
        }
        return view('register');
    }

    // Handle registration using only fillables: name and password
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:users,name|max:255',
            'password' => 'required|string|min:4'
        ]);

        // Using Eloquent create with fillable fields
        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        // REQUIREMENT 5: PRG pattern via redirect flash message
        return redirect()->route('login')->with('success', 'Registration successful! You can now log in.');
    }
}
