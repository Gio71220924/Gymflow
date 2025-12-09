<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

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

            if (! auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

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
            'status'   => User::STATUS_INACTIVE,
        ]);

        event(new Registered($user)); // kirim email verifikasi bawaan Laravel
        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', 'Registrasi berhasil. Cek email untuk verifikasi.');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
