<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthentikasiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TrainerController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;


Route::group(['middleware' => ['auth','verified']], function () {

    // Dashboard
    Route::get('/home', [PageController::class, 'home'])->name('home');

    // Classes (rename method agar tidak bentrok keyword)
    Route::get('/class', [PageController::class, 'classPage'])->name('class');
    Route::get('/class/searching', [PageController::class, 'searchclass'])->name('search-class');
    Route::get('/class/create', [PageController::class, 'createClassForm'])->name('class.create');
    Route::post('/class', [PageController::class, 'storeClass'])->name('class.store');
    Route::post('/class/{id}/join', [PageController::class, 'joinClass'])->name('class.join');
    Route::post('/class/{id}/cancel', [PageController::class, 'cancelClassBooking'])->name('class.cancel');
    Route::delete('/class/{classId}/kick/{bookingId}', [PageController::class, 'kickClassMember'])->name('class.kick');
    Route::get('/class/{id}/edit', [PageController::class, 'editClassForm'])->name('class.edit');
    Route::put('/class/{id}', [PageController::class, 'updateClass'])->name('class.update');
    Route::delete('/class/{id}', [PageController::class, 'deleteClass'])->name('class.destroy');


    // Billing
    Route::get('/billing', [PageController::class, 'billing'])->name('billing');
    Route::post('/billing', [PageController::class, 'storeInvoice'])->name('billing.store');
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

    // Member self-profile setup (user-only)
    Route::get('/member/profile/setup', [PageController::class, 'memberProfileForm'])->name('member.profile.setup');
    Route::post('/member/profile/setup', [PageController::class, 'memberProfileSave'])->name('member.profile.save');

    // Users (CRUD by admin)
    Route::get('/users', [UserController::class, 'getuser'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Change Password
    Route::get('/change-password', [UserController::class, 'changePasswordForm'])->name('change-password');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('update-password');

    // Membership renewal (user)
    Route::post('/membership/renew', [PageController::class, 'renewMembership'])->name('membership.renew');

    // Trainers (admin)
    Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
    Route::get('/trainers/create', [TrainerController::class, 'create'])->name('trainers.create');
    Route::post('/trainers', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/trainers/{id}/edit', [TrainerController::class, 'edit'])->name('trainers.edit');
    Route::put('/trainers/{id}', [TrainerController::class, 'update'])->name('trainers.update');
    Route::delete('/trainers/{id}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
    });

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthentikasiController::class, 'loginForm'])->name('login');
    Route::post('/CekLogin' , [AuthentikasiController::class, 'cekLogin'])->name('cekLogin');
    Route::get('/register', [AuthentikasiController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthentikasiController::class, 'register'])->name('register.save');
    // Password Reset (Laravel bawaan)
    Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
    Route::get('/', [PageController::class, 'landing'])->name('landingpage');
});

// Email Verification routes + logout (auth but not necessarily verified)
Route::group(['middleware' => ['auth']], function () {
    // Logout (harus bisa diakses meski belum terverifikasi)
    Route::post('/logout', [AuthentikasiController::class, 'logout'])->name('logout');

    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request) {
        $user = $request->user();

        if (! $user || $user->getKey() != $request->route('id')) {
            abort(403);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/home');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if ($user->status !== \App\User::STATUS_ACTIVE) {
            $user->status = \App\User::STATUS_ACTIVE;
            $user->save();
        }

        if ($user->role === \App\User::ROLE_USER && ! $user->memberGym) {
            return redirect()->route('member.profile.setup')->with('success', 'Verifikasi berhasil. Lengkapi profil member Anda.');
        }

        return redirect('/home');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Link verifikasi dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});
