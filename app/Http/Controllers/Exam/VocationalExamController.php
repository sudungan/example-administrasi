<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VocationalExam;
use App\Helpers\{HttpCode, MainRole};

class VocationalExamController extends Controller
{
    public function index() {
        return view('exams.vocational-exam.index');
    }

    public function getListVocationalExam() {
        try {
            $listVocationalExam = VocationalExam::with('major')->get();
            return response()->json([
                'message'  => 'get list vocational exam succesfully',
                'data'  => $listVocationalExam,
            ], HttpCode::OK
        );

        } catch (\Exception $error) {
            return response()->json([
                'message'  => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
