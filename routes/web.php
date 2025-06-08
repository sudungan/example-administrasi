<?php

use App\Http\Controllers\General\{
    ClassroomController,
    UserController,
};
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('classrooms', [ClassroomController::class, 'index'])->name('classrooms');
    Route::redirect('roles', 'roles');
    Volt::route('roles', 'roles.index')->name('roles.index');

    Route::redirect('majors', 'major');
    Volt::route('majors', 'majors.index')->name('majors.index');
    // Route::redirect('users', 'users');
    // Volt::route('users', 'users.index')->name('users.index');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('user/{userId}', [UserController::class, 'showUser']);
    Route::get('listUser', [UserController::class, 'getAllUser'])->name('listUser');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
