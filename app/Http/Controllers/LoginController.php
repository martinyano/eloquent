<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm(Request $request)
    {
        // REQUIREMENT 3: If already logged in, cannot go back to login page
        if ($request->session()->has('user_id')) {
            return redirect()->route('eloquent.index');
        }
        return view('login');
    }

    // Handle login with attempt trapping (Requirement 2)
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user) {
            return back()->with('error', 'Name not found.');
        }

        // REQUIREMENT 2: Check if account is locked out due to too many failed attempts
        if ($user->lockout_time && Carbon::now()->lt($user->lockout_time)) {
            $remaining = Carbon::now()->diffInSeconds(Carbon::parse($user->lockout_time));
            return back()->with('error', "Too many failed attempts! Account locked. Try again in {$remaining} seconds.");
        }

        // Reset lockout if time has passed
        if ($user->lockout_time && Carbon::now()->gte($user->lockout_time)) {
            $user->update([
                'failed_attempts' => 0,
                'lockout_time' => null
            ]);
            $user->failed_attempts = 0;
        }

        // Verify password
        if (Hash::check($request->password, $user->password)) {
            // Reset failed attempts on success
            $user->update([
                'failed_attempts' => 0,
                'lockout_time' => null
            ]);

            // Set session
            $request->session()->put('user_id', $user->id);
            $request->session()->put('name', $user->name);

            return redirect()->route('eloquent.index');
        } else {
            // Increment failed attempts
            $attempts = $user->failed_attempts + 1;

            if ($attempts >= 5) {
                $lockoutUntil = Carbon::now()->addMinutes(3); // Lock for 3 minutes
                $user->update([
                    'failed_attempts' => $attempts,
                    'lockout_time' => $lockoutUntil
                ]);
                return back()->with('error', 'Too many wrong password attempts! Account locked for 3 minutes.');
            } else {
                $user->update([
                    'failed_attempts' => $attempts
                ]);
                $left = 5 - $attempts;
                return back()->with('error', "Wrong password! You have {$left} attempt(s) left.");
            }
        }
    }

    // Logout
    public function logout(Request $request)
    {
        // 1. Flush all session data
        $request->session()->flush();

        // 2. Invalidate the session and regenerate the CSRF token (Crucial for security)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Redirect to login with no-cache headers
        return redirect()->route('login');
    }
}