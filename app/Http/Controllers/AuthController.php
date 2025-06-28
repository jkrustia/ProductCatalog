<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Spatie\Permission\Models\Role; // Import Spatie Role model

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
    }

    public function showRegistrationForm()
    {
        return view('auth.signup'); // Or 'auth.register' if you named the file that way
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the default 'User' role to the newly registered user
        $userRole = Role::where('name', 'User')->first(); //
        if ($userRole) { //
            $user->assignRole($userRole); //
        } else { //
            // Handle case where 'User' role does not exist (e.g., log error or create it)
            // For now, let's assume it exists or will be created by a seeder.
        }

        Auth::login($user); // Log in the new user

        // Redirect to a default page or a user-specific dashboard
        return redirect()->route('home')->with('success', 'Registration successful! Welcome.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}