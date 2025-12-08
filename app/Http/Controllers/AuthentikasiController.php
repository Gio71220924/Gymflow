<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthentikasiController extends Controller
{
    public function loginForm()
    {
        return view('login', [
            'key' => 'login',
        ]);
    }
    public function cekLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }
        return back()->withErrors([
            'email' => 'Email atau password anda salah, silahkan coba kembali.',
        ])->onlyInput('email');
    }

    public function registerForm()
    {
        return view('register', [
            'key' => 'register',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => User::ROLE_USER,
            'status'   => User::STATUS_ACTIVE,
        ]);

        event(new Registered($user));
        auth()->login($user);

        return redirect()->route('verification.notice')->with('success', 'Registrasi berhasil, silakan verifikasi email Anda.');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
