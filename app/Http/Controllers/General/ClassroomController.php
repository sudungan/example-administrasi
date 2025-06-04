<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ClassroomController extends Controller
{
    public function index() {
        return view('classrooms.index');
    }

    public function getAllUser(Request $request) {
        try {
           dd($request);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
