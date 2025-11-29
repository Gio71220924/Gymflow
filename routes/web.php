<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthentikasiController;


Route::group(['middleware' => ['auth']], function () {

    // Dashboard
    Route::get('/home', [PageController::class, 'home'])->name('home');

    // Classes (rename method agar tidak bentrok keyword)
    Route::get('/class', [PageController::class, 'classPage'])->name('class');

    // Billing
    Route::get('/billing', [PageController::class, 'billing'])->name('billing');
    Route::put('/billing/{id}', [PageController::class, 'updateInvoiceStatus'])->name('billing.update');
    Route::get('/billing/{id}/print', [PageController::class, 'printInvoice'])->name('billing.print');

    // Settings
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');
    Route::post('/settings', [PageController::class, 'saveSettings'])->name('settings.save');

    // Member
    Route::get('/member', [PageController::class, 'member'])->name('member');
    Route::get('/member/add-member', [PageController::class, 'addMemberForm'])->name('add-member');
    Route::post('/member/add-member/save', [PageController::class, 'saveMember'])->name('save-member');
    Route::get('/member/edit-member/{id}', [PageController::class, 'editMemberForm'])->name('edit-member');
    Route::put('/member/edit-member/update/{id}', [PageController::class, 'updateMember'])->name('update-member');

    // Hapus: idealnya DELETE, tapi kalau masih pakai link GET boleh aktifkan salah satu:
    Route::delete('/member/delete-member/{id}', [PageController::class, 'deleteMember'])->name('delete-member');
    // Sementara fallback GET (kalau tombol masih <a href="...">):
    Route::get('/member/delete-member/{id}', [PageController::class, 'deleteMember']);

    // Users (CRUD by admin)
    Route::get('/users', [UserController::class, 'getuser'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Change Password
    Route::get('/change-password', [UserController::class, 'changePasswordForm'])->name('change-password');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('update-password');
    
    // Logout   
    Route::post('/logout', [AuthentikasiController::class, 'logout'])->name('logout');
    });

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthentikasiController::class, 'loginForm'])->name('login');
    Route::post('/CekLogin' , [AuthentikasiController::class, 'cekLogin'])->name('cekLogin');
    Route::get('/register', [AuthentikasiController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthentikasiController::class, 'register'])->name('register.save');
    Route::get('/', [PageController::class, 'landing'])->name('landingpage');
});
