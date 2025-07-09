<?php

namespace App\Http\Controllers\Kurikulum;

use App\Exceptions\NotFoundException;
use App\Helpers\{HttpCode, MainRole};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{AdditionRole, Classroom, Major, User, };
use Illuminate\Support\Facades\Validator;

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
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getListTeachers() {
        try {
            $listTeacher = User::select('id', 'name')->where('role_id', MainRole::item['guru'])->get();
             return response()->json([
                'message'   => 'get list teacher successfully',
                'data'      => $listTeacher
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getHomeRomeTeacherId() {
         try {
            $headMajorById = AdditionRole::where('slug', 'wali-kelas')->value('id');
                if (!$headMajorById) {
                    throw new NotFoundException(
                    'Wali Kelas belum tersedia',
                    ['addition_role_id' => ['Jabatan Wali Kelas belum tersedia.']],
                    HttpCode::NOT_FOUND
                );
            }

            return response()->json([
                'message'   => 'get head-major by successfully',
                'data'      => $headMajorById
            ], HttpCode::OK);
        } catch(NotFoundException $error) {
             return $error->render(request());
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getListStudent() {
        try {
            $listStudent = User::select('id', 'name')->where('role_id', MainRole::item['siswa'])->doesntHave('classroom')->get();
             return response()->json([
                'message'   => 'get list student successfully',
                'data'      => $listStudent
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getListMajor() {
        try {
            $listMajor = Major::select('id', 'name')->get();
             return response()->json([
                'message'   => 'get list major successfully',
                'data'      => $listMajor
            ], HttpCode::OK);
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

    public function storeDataClassroom(Request $request) {
        try {
             $validator = Validator::make($request->all(), [
                'name'                => ['required', 'max:255'],
                'teacher_id'          => 'required',
                'student_ids.*'       => 'required|integer|exists:users,id',
                'major_id'              => 'required'
            ], [
                'name.unique'           => 'Nama Kelas sudah digunakan..',
                'teacher_id.required'   => 'Nama Guru wajib dipilih',
                'teacher_id.unique'     => 'Nama Guru wajib dipakai',
                'student_ids.required'  => 'Nama Siswa Harus dipilih..',
                'major_id.required'     => 'Nama Jurusan Harus dipilih..',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validated();

            dd($validated);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
