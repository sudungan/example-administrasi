<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index() {
        return view('majors.index');
    }

    public function getListMajor() {
        try {
            $majors = Major::with('headMajor')->get();
            return response()->json([
                'message'   => 'get list major successfully',
                'data'      => $majors
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }
}
