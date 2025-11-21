<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthentikasiController extends Controller
{
    public function loginForm()
    {
        return view('login', [
            'key' => 'login',
        ]);
    }
}
