<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penulis',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran Berhasil!');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user(); 

            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Login Berhasil!');
            } elseif ($user->role === 'penulis') {
                return redirect()->route('home')->with('success', 'Login Berhasil!');
            }

            return redirect('/')->with('error', 'Role tidak dikenali.');
        }

        return back()->withErrors(['password' => 'Email atau password salah.']);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('/')->with('success', 'Berhasil Logout!');
    }
}
