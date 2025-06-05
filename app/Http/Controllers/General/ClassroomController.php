<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\User;

class ClassroomController extends Controller
{
    public function index() {
        $listClassroom = Classroom::all();
        return view('classrooms.index', compact('listClassroom'));
    }

    public function getAllUser(Request $request) {
        try {
           dd($request);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
