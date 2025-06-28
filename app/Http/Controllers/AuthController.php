<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class AuthController extends Controller
{

    public function showLoginForm()
    {
        // Render the login form view
        return view('auth.login');
    }

    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the user in using email
        if (Auth::attempt($request->only('email', 'password'))) {
        $role = Auth::user()->getRoleNames()->first(); // Get user role

        switch ($role) {
            case 'Super Admin':
                return redirect()->route('superadmin.dashboard');
            case 'Product Manager':
                return redirect()->route('pm.dashboard');
            case 'Admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect('/home'); // Default redirect
        }
    }

        // If login fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);

        

        // User::create([
        //     'username' => $request->username,
        //     'password' => Hash::make($request->password), // Secure password hashing
        // ]);

        // Temporarily redirect to the dashboard without processing form data
        //return redirect()->route('dashboard')->with('success', 'Welcome to your dashboard!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
// This controller handles user registration
// It validates the input, creates a new user, and redirects to the home page with a success message.
// Make sure to import the User model and Hash facade for password hashing.
// You can add more methods for login, logout, etc. as needed.