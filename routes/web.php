<?php


use Illuminate\Support\Facades\Route; // BENAR



Route::get('/', 'PageController@home')->name('home');



Route::get("/class", "PageController@class")->name("class");

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



