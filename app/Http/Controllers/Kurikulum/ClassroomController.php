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

            if ($listTeacher->isEmpty()) {
                    throw new NotFoundException(
                    'Data guru belum tersedia',
                    ['teacher_id' => ['Data guru belum tersedia.']],
                    HttpCode::NOT_FOUND
                );
            }
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
            $listStudent = User::select('id', 'name')->where('role_id', MainRole::item['siswa'])->with('classroom')->get();

            if ($listStudent->isEmpty()) {
               throw new NotFoundException(
                    'Data siswa belum tersedia',
                    ['student_id' => ['User siswa belum tersedia..']],
                    HttpCode::NOT_FOUND
                );
            }

            return response()->json([
                'message'   => 'get list student successfully',
                'data'      => $listStudent
            ], HttpCode::OK);


        } catch(NotFoundException $error) {
             return $error->render(request());
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getListMajor() {
        try {
            $listMajor = Major::select('id', 'name')->get();

            if($listMajor->isEmpty()) {
                throw new NotFoundException(
                    'Data Jurusan belum tersedia',
                    ['major_id' => ['Data Jurusan belum tersedia.']],
                    HttpCode::NOT_FOUND
                );
            }

            return response()->json([
                'message'   => 'get list major successfully',
                'data'      => $listMajor
            ], HttpCode::OK);


        } catch(NotFoundException $error) {
             return $error->render(request());
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
                'teacher_id'          => 'required|unique:classrooms,id',
                'student_ids.*'       => 'required|integer|exists:users,id',
                'student_ids'         => 'required|array|min:1',
                'student_ids.*'       => 'required|integer|exists:classrooms,id',
                'major_id'              => 'required'
            ], [
                'name.unique'               => 'Nama Kelas sudah digunakan..',
                'name.required'             => 'Nama Kelas harus diisi..',
                'teacher_id.required'       => 'Nama Guru wajib dipilih',
                'teacher_id.unique'         => 'Nama Guru wajib dipakai',
                'student_ids.*.required'    => 'Nama Siswa Harus dipilih..',
                'student_ids.min'           => 'Minimal satu siswa harus dipilih.',
                'student_ids.required'      => 'Minimal satu siswa harus dipilih.',
                'student_ids.*.integer'     => 'ID siswa tidak valid.',
                'student_ids.*.exists'      => 'Salah satu siswa tidak ditemukan.',
                'major_id.required'         => 'Nama Jurusan Harus dipilih..',
            ]);

            // mengecek user student yang sudah terdaftar dikelas lain
            $validator->after(function ($validator) use ($request) {
                $students = User::with('classroom')->whereIn('id', $request->get('student_ids'))->get();

                foreach ($students as $student) {
                    if ($student->classroom_id !== null) {
                        $validator->errors()->add(
                            'student_ids',
                            "{$student->name} sudah tergabung dalam kelas {$student->classroom->name}."
                        );
                    }
                }
            });

            // mengecek user guru yang sudah terdaftar dikelas lain sebagai wali kelas
             $validator->after(function ($validator) use ($request) {
                $classroom = Classroom::where('teacher_id', $request->get('teacher_id'))->first();
                $validator->errors()->add(
                    'teacher_id',
                    "{$classroom->teacher->name} sudah menjadi wali kelas {$classroom->name}."
                );
            });

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validated();

            $classroom = Classroom::create([
                'major_id'      => $validated['major_id'],
                'teacher_id'    => $validated['teacher_id'],
                'name'          => $validated['name']
            ]);

            // whereIn bisa menampung id lebih dari satu
            User::whereIn('id', $validated['student_ids'])->update(['classroom_id' => $classroom->id]);

            return response()->json([
                'message'   => 'created successfully',
            ], HttpCode::CREATED);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateClassroom(Request $request, $classroomId) {
        dd($classroomId);
    }

    public function getEditClassroomBy($classroomId) {
        try {
            $classroom = Classroom::where('id', $classroomId)->with(['major', 'teacher', 'students'])->first();
            return response()->json([
                'message'   => 'get classrrom successfully',
                'data'      => $classroom
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
