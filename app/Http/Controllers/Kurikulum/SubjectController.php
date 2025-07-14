<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index() {
        return view('subjects.with-vue.index');
    }

    public function getListSubject() {
        try {
            $listSubject = Subject::with(['teacher', 'classroom'])->get();
            return response()->json([
                'message'   => 'get list subject successfully',
                'data'      => $listSubject
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'=> $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function storeSubject(Request $request) {
        dd($request);
    }
}
