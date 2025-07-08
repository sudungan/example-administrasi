<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    public function index() {
        return view('classrooms.with-vue.index');
    }

    public function getListClassroom() {
        try {
            $listClassroom = Classroom::with(['major', 'teacher', 'students'])->get();

            return response()->json([
                'message'   => 'get list classroom successfully',
                'data'      => $listClassroom
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getClassroomBy($classroomId) {
        try {
            $listClassroom = Classroom::with(['major', 'teacher', 'students'])->findOrFail($classroomId);

            return response()->json([
                'message'   => 'get classroom with students successfully',
                'data'      => $listClassroom
            ], HttpCode::OK);
            
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
