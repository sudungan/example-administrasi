<?php

use App\Http\Controllers\General\{RoleController, UserController};
use App\Http\Controllers\Kurikulum\{MajorController, ClassroomController};
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // ROUTE FOR CLASSROOMS
    Route::get('classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('get-list-classroom', [ClassroomController:: class, 'getListClassroom'])->name('get-list-classroom');

    // ROUTE FOR ROLES
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('listRole', [RoleController::class, 'getListRole'])->name('listRole');
    Route::post('store-role', [RoleController::class, 'store'])->name('store-role');
    Route::get('edit-addition-role/{additionRoleId}', [RoleController::class, 'editAdditionRole'])->name('edit-addition-role');
    Route::put('update-addition-role/{additionRoleId}', [RoleController::class, 'updateAdditionRole'])->name('update-addition-role');
    Route::delete('delete-addition-role/{additionRoleId}', [RoleController::class, 'deleteAdditionRole'])->name('delete-addition-role');
    // getListRole

    // ROUTE FOR MAJORS
    Route::get('majors', [MajorController::class, 'index'])->name('majors.index');
    Route::get('list-major', [MajorController::class, 'getListMajor'])->name('list-major');
    Route::get('edit-major/{majorId}', [MajorController::class, 'getMajorBy'])->name('edit-major');
    Route::get('list-get-teacher', [MajorController::class, 'getListTeacher'])->name('list-get-teacher');
    Route::post('store-major', [MajorController::class, 'storeMajor'])->name('store-major');
    Route::put('update-major/{major}', [MajorController::class, 'updateMajor'])->name('update-major');
    Route::delete('delete-major/{major}', [MajorController::class, 'deleteMajor'])->name('delete-major');

    // delete-major
    // ROUTE FOR USERS
    Route::get('list-role', [UserController::class, 'getListRole'])->name('list-role');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('store-data-user-general', [UserController::class, 'storeDataUserGeneral']);
    Route::get('user/{userId}', [UserController::class, 'showUser'])->name('user');
    Route::get('user-profile-by/{userId}', [UserController::class, 'getProfileUserBy'])->name('user-profile-by');
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
