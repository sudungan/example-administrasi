<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index() {
        return view('subjects.with-vue.index');
    }

    public function getListSubject() {
        try {
            $listSubject = Subject::with(['teacher', 'classroom.major'])->get();
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
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'name' => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],
                'user_id'   => ['required'],
                'classroom_id'  => 'required',
                'jumlah_jp'  => 'required',
                'colour'    => 'required'
                ], [
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'user_id.required'=> 'Nama Kepala Jurusan wajib dipilih',
                'classroom_id.required'   => 'Kelas harus dipilih',
                'jumlah_jp.required' => 'Total Jam Pelajaran wajib dipilih..',
                'colour.required'   => 'Warna wajib dipilh'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();

            $subject = Subject::create([
                'name'          =>  $validated['name'],
                'user_id'       =>  $validated['user_id'],
                'classroom_id'  =>  $validated['classroom_id'],
                'colour'        =>  $validated['colour'],
                'jumlah_jp'     =>  $validated['jumlah_jp']
            ]);

            return response()->json(
                [ 'message'   => 'subject created succesfully', ],
                HttpCode::CREATED
            );

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
