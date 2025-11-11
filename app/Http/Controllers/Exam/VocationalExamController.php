<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VocationalExam;
use App\Helpers\HttpCode;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\{ConflictException, NotFoundException};
use League\Uri\Http;

class VocationalExamController extends Controller
{
    public function index() {
        return view('exams.vocational-exam.index');
    }

    public function getListVocationalExam() {
        try {
            $listVocationalExam = VocationalExam::with('vactionExamMajors', 'examDays')->get();
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

    public function storeVocationalExam(Request $request) {
        try {
            //melakukan validasi kiriman request dari front-end
             $validator = Validator::make($request->all(), [
                'period'                   => ['required'],
                'description'               => 'required',
                'name'                      => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/','unique:' . VocationalExam::class],
            ], [
                'name.unique'           => 'Nama Jurusan sudah digunakan..',
                'name.required'         => 'Nama Jurusan sudah digunakan..',
                'period.min'            => 'Periode Wajib disi..',
                'name.regex'            => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'description.required'  => 'Tema Ujian wajib disi..',
            ]);
            
            // mengirimkan response json ketika request fail berdasarkan rule validasi
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            // memasukan object validator kedalam validated karna sudah divalidasi
            $validated = $validator->validated();

            // melakukan store data kedalam class model VocationalExam
            VocationalExam::create($validated);
            
            // mengirimkan pesan sukses berbentuk json ke front-end
            return response()->json([
                'message'   => 'Exam Created Successfully'
            ], HttpCode::CREATED);

        } catch (\Exception $error) {
            //throw $th;
        }
    }
}
