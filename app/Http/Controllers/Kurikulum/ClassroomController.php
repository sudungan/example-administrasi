<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    public function index() {
        return view('classrooms.index');
    }

    public function getListClassroom() {
        try {
            $listClassroom = Classroom::with(['major', 'teacher'])->get();
            return response()->json([
                'message'   => 'get list classroom successfully',
                'data'      => $listClassroom
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }
}
