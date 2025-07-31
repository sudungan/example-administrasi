<?php

use App\Http\Controllers\General\{RoleController, UserController};
use App\Http\Controllers\Kurikulum\{
    MajorController, ClassroomController, SubjectController
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

    // ROUTE FOR SUBJECT
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('store-subject', [SubjectController::class, 'storeSubject'])->name('store-subject');
    Route::post('store-teacher-colour', [SubjectController::class, 'storeTeacherColour'])->name('store-teacher-colour');
    Route::get('check-base-colour-by/{teacherId}', [SubjectController::class, 'checkBaseTeacherSubject'])->name('check-base-colour-by');
    Route::get('list-teacher-subject', [SubjectController::class, 'getListTeacherSubject'])->name('list-teacher-subject');
    Route::get('teacher-by/{teacherId}', [SubjectController::class, 'getTeacherBy'])->name('teacher-by');
    Route::get('list-subject-by/{teacherId}', [SubjectController::class, 'getListSubjectBy'])->name('list-subject-by');

    // ROUTE FOR CLASSROOMS
    Route::get('classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('list-classroom', [ClassroomController:: class, 'getListClassroom'])->name('list-classroom');
    Route::get('list-teacher', [ClassroomController::class, 'getListTeachers'])->name('list-teacher');
    Route::get('list-student', [ClassroomController::class, 'getListStudent'])->name('list-student');
    Route::get('list-major', [ClassroomController::class, 'getListMajor'])->name('list-major');
    Route::get('home-rome-teacher-id', [ClassroomController::class, 'getHomeRomeTeacherId'])->name('home-rome-teacher-id');
    Route::get('detail-classroom-by/{classroomId}', [ClassroomController::class, 'getDetailClassroomBy'])->name('detail-classroom-by');
    Route::get('edit-classroom-by/{classroomId}', [ClassroomController::class, 'getEditClassroomBy'])->name('edit-classroom-by');
    Route::post('store-classroom', [ClassroomController::class, 'storeDataClassroom'])->name('store-classroom');
    Route::put('update-classroom-by/{classroomId}', [ClassroomController::class, 'updateClassroom'])->name('');
    Route::delete('delete-classroom-by/{classroom}', [ClassroomController::class, 'deleteClassroomBy'])->name('delete-classroom-by');

    // ROUTE FOR ROLES
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('list-role', [RoleController::class, 'getListRole'])->name('list-role');
    Route::post('store-role', [RoleController::class, 'store'])->name('store-role');
    Route::get('edit-addition-role/{additionRoleId}', [RoleController::class, 'editAdditionRole'])->name('edit-addition-role');
    Route::put('update-addition-role/{additionRoleId}', [RoleController::class, 'updateAdditionRole'])->name('update-addition-role');
    Route::delete('delete-addition-role/{additionRoleId}', [RoleController::class, 'deleteAdditionRole'])->name('delete-addition-role');

    // ROUTE FOR MAJORS
    Route::get('majors', [MajorController::class, 'index'])->name('majors.index');
    Route::get('list-major', [MajorController::class, 'getListMajor'])->name('list-major');
    Route::get('edit-major/{majorId}', [MajorController::class, 'getMajorBy'])->name('edit-major');
    Route::get('list-teacher', [MajorController::class, 'getListTeacher'])->name('list-teacher');
    Route::get('head-major-byId', [MajorController::class, 'getHeadMajorById'])->name('head-major-byId');
    Route::post('store-major', [MajorController::class, 'storeMajor'])->name('store-major');
    Route::put('update-major/{major}', [MajorController::class, 'updateMajor'])->name('update-major');
    Route::delete('delete-major/{major}', [MajorController::class, 'deleteMajor'])->name('delete-major');

    // ROUTE FOR USERS
    Route::get('list-role-to-user', [UserController::class, 'getListRole'])->name('list-role-to-user');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('store-data-user-general', [UserController::class, 'storeDataUserGeneral']);
    Route::get('edit-user-by/{userId}', [UserController::class, 'getEditUserBy'])->name('edit-user-by');
    Route::put('update-user-by/{userId}', [UserController::class, 'updateUserBy'])->name('update-user-by');
    Route::get('show-user-by/{userId}', [UserController::class, 'showUser'])->name('show-user-by');
    Route::get('user-profile-by/{userId}', [UserController::class, 'getProfileUserBy'])->name('user-profile-by');
    Route::get('addition-role/{roleId}', [UserController::class, 'getAdditionRole'])->name('addition-role');
    Route::get('list-user', [UserController::class, 'getListUser'])->name('list-user');
    Route::get('search-user', [UserController::class, 'searchUser'])->name('search-user');
    Route::get('select-addition-role/{roleId}', [UserController::class, 'getSelectedRole'])->name('select-addition-role');
    Route::delete('delete-user/{userId}', [UserController::class, 'deleteUser'])->name('delete-user');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
