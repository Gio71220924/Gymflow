<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Member_Gym;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['key' => 'home']);
    }

    public function member()
    {
        $members = Member_Gym::orderByDesc('id')->get();

        return view('member', [
            'key'     => 'member',
            'members' => $members,
        ]);
    }

    public function class()
    {
        return view('class', ['key' => 'class']);
    }

    public function addMemberForm()
    {
        return view('add-member', ['key' => 'member']);
    }
}