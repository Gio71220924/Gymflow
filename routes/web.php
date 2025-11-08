<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home (contoh)

use Illuminate\Support\Facades\Route; // BENAR



Route::get('/home', 'PageController@home')->name('home');



Route::get("/class", "PageController@class")->name("class");

//Routing untuk get member
Route::get('/member', 'PageController@member')->name('member');
// Routing ke form tambah member
Route::get('/member/add-member', "PageController@addMemberForm")->name('add-member');
//Routing untuk save data form
Route::post('/member/add-member/save', 'PageController@saveMember')->name('save-member');


// MemberGym



