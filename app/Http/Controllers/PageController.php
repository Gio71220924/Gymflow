<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use app\Member_Gym;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['key' => 'home']);
    }

    public function member()
    {
        return view('member', ['key' => 'member']);
    }

    public function class()
    {
        return view('class', ['key' => 'class']);
    }
}