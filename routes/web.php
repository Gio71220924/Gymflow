<?php


use Illuminate\Support\Facades\Route; // BENAR



Route::get('/home', 'PageController@home')->name('home');



Route::get("/class", "PageController@class")->name("class");
Route::get('/billing', 'PageController@billing')->name('billing');
Route::put('/billing/{id}', 'PageController@updateInvoiceStatus')->name('billing.update');
Route::get('/billing/{id}/print', 'PageController@printInvoice')->name('billing.print');

//Routing untuk get member
Route::get('/member', 'PageController@member')->name('member');
// Routing ke form tambah member
Route::get('/member/add-member', "PageController@addMemberForm")->name('add-member');
//Routing untuk save data form
Route::post('/member/add-member/save', 'PageController@saveMember')->name('save-member');
// Routing ke form edit member
Route::get('/member/edit-member/{id}', 'PageController@editMemberForm')->name('edit-member');
// Routing untuk update data member
Route::put('/member/edit-member/update/{id}', 'PageController@updateMember')->name('update-member');
// Routing untuk delete data member
Route::get('/member/delete-member/{id}', 'PageController@deleteMember')->name('delete-member');
// MemberGym

// Users (CRUD by admin)
Route::get('/users', 'UserController@getuser')->name('users.index');
Route::get('/users/create', 'UserController@create')->name('users.create');
Route::post('/users', 'UserController@store')->name('users.store');
Route::delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

//Authentikasi
Route::get('/', 'AuthentikasiController@loginForm')->name('login');

