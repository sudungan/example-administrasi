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

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('store-data-user-general', [UserController::class, 'storeDataUserGeneral']);
    Route::get('user/{userId}', [UserController::class, 'showUser'])->name('user');
    Route::get('addition-role/{roleId}', [UserController::class, 'getAdditionRole'])->name('addition-role');
    Route::get('list-user', [UserController::class, 'getListUser'])->name('list-user');
    Route::get('search-user', [UserController::class, 'searchUser'])->name('search-user');
    Route::get('select-addition-role/{roleId}', [UserController::class, 'getSelectedRole'])->name('select-addition-role');


    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
