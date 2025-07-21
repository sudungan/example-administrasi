<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\{HttpCode, MainRole};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{AdditionRole, Classroom, Major, User, };
use App\Exceptions\{ConflictException, NotFoundException};
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

    public function getDetailClassroomBy($classroomId) {
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
                'name'                => 'required', 'max:255',
                'teacher_id'          => ['required'],
                'student_ids.*'       => 'required|integer|exists:users,id',
                'student_ids'         => 'required|array|min:1',
                'student_ids.*'       => 'required|integer',
                'major_id'            => 'required'
            ], [
                'name.required'             => 'Nama Kelas harus diisi..',
                'teacher_id.required'       => 'Nama Guru wajib dipilih',
                'student_ids.*.required'    => 'Nama Siswa Harus dipilih..',
                'student_ids.min'           => 'Minimal satu siswa harus dipilih.',
                'student_ids.required'      => 'Minimal satu siswa harus dipilih.',
                'student_ids.*.integer'     => 'ID siswa tidak valid.',
                'major_id.required'         => 'Nama Jurusan Harus dipilih..',
            ]);

            // mengecek user student yang sudah terdaftar dikelas lain
            $validator->after(function ($validator) use ($request) {
                $students = User::with('classroom')->whereIn('id', $request->get('student_ids'))->get();
                $major = Major::where('id', $request->get('major_id'))->first();

                foreach ($students as $student) {
                    if ($student->classroom_id !== null) {
                        $validator->errors()->add(
                            'student_ids',
                            "{$student->name} sudah bergabung dikelas {$student->classroom->name}-{$major->initial}."
                        );
                    }
                }
            });


            // mengecek user guru yang sudah terdaftar dikelas lain sebagai wali kelas
            $validator->after(function ($validator) use ($request) {
                $classroom = Classroom::where('teacher_id', $request->get('teacher_id'))->first();
                if ($classroom) {
                    $validator->errors()->add(
                        'teacher_id',
                        "{$classroom->teacher->name} sudah menjadi wali kelas {$classroom->name}-{$classroom->major->initial}."
                    );
                }
            });

            // mengecek nama classroom yang terdaftar dijurusan
            $validator->after(function ($validator) use ($request) {
                $classroom = Classroom::where('name', $request->get('name'))->where('major_id', $request->get('major_id'))->first();
                if ($classroom) {
                    $validator->errors()->add(
                        'name',
                        "Nama Kelas {$classroom->name} sudah ada di jurusan {$classroom->major->initial}."
                    );
                }
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

    public function deleteClassroomBy(Classroom $classroom) {
        try {
            if (Classroom::where('id', $classroom['id'])->whereHas('students')->exists()) {
               throw new ConflictException('kelas sudah memiliki siswa', [
                    'classroom_id' => 'Tidak dapat dihapus karena masih digunakan'
                ]);
            }
            $classroom->delete();

            return response()->json([
                'message'   => 'classroom deleted succesfully',
            ], HttpCode::OK);
        } catch(ConflictException $exception) {
            return $exception->render(request());
        }catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }
}
