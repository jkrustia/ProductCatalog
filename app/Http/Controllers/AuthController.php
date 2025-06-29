<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        // Change this line:
        return view('auth.login'); // Now points directly to resources/views/login.blade.php
    }

    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $role = Auth::user()->getRoleNames()->first();

            switch ($role) {
                case 'Super Admin':
                    return redirect()->route('superadmin.dashboard');
                case 'Product Manager':
                    // Original code used 'pm.dashboard', changed to 'productmanager.dashboard' based on web.php
                    return redirect()->route('pm.dashboard');
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                default:
                    // Redirect to the new 'home' route for authenticated users
                    return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        // Change this line:
        return view('auth.signup'); // Now points directly to resources/views/signup.blade.php
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

        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $user->assignRole($userRole);
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect to the guest landing page after logout
        return redirect()->route('welcome');
    }
}