<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'max:255',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],
        ], [
            'password.regex' => 'The password must be at least 8 characters long and include at least one uppercase letter, one number, and one special character.'
        ]);

        $name = explode('@', $credentials['email'])[0];
        $credentials['name'] = $name;

        $user = User::create($credentials);
        Auth::login($user);

        return redirect()->route('all')->with([
            'message' => 'Welcome to CodeBox!',
            'type' => 'notif']
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ], [
            'email.exists' => 'The email address is not registered in our system.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('all')->with([
                'message' => 'Welcome back! We missed you.',
                'type' => 'notif']
            );
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
