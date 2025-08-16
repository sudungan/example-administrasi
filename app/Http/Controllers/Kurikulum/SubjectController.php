<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\{TeacherColour, Classroom, Subject, SubjectTeacher, User, ScheduleSubject};
use App\Exceptions\{ConflictException, NotFoundException};
use App\Helpers\MainRole;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index() {
        return view('subjects.with-vue.index');
    }

    public function getListSubjectBy($teacherId) {
        try {
            $subjects = Subject::where('user_id', $teacherId)->with(['teacher', 'classroomSubject.major'])->get();
            // dd($subjects);
            return response()->json([
                'message'   => 'get list subject by teacher successfully',
                'data'      => $subjects
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    public function getTeacherBy($teacherId) {
        try {
            $teacher = User::select('id', 'name')->where('role_id', MainRole::item['guru'])->where('id', $teacherId)->first();
            return response()->json([
                'message'   => 'get data teacher successfully',
                'data'      => $teacher
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getListTeacherSubject() {
        try {
            $listTeacherSubject = Subject::with(['teacher','classroomSubject', 'teacherJp'])->get();
            // dd($listTeacherSubject);
            $teachers = User::where('role_id', MainRole::item['guru'])->with(['subjects.classroomSubject.major','amountSubjects'])->get();
            return response()->json([
                'message'   => 'get list teacher successfully',
                'data'      => $teachers
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function storeSubject(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'name' => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],
                'user_id'   => ['required'],
                'classrooms_subject'  => 'required',
                'colour'    => 'required'
                ], [
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'user_id.required'=> 'Nama Kepala Jurusan wajib dipilih',
                'classrooms_subject.required'   => 'Kelas harus dipilih',
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
                'colour'        =>  $validated['colour'],
            ]);

            $totalJpByClassrooms = 0;
            $pivotData = [];
            foreach ($validated['classrooms_subject'] as $classroom) {
                // $subject->classroomSubject()->attach($classroom['classroom_id'],
                // [
                //     'jumlah_jp' => $classroom['jumlah_jp']
                // ]);
                $pivotData[$classroom['classroom_id']] = [
                    'jumlah_jp' => $classroom['jumlah_jp']
                ];
                $totalJpByClassrooms += $classroom['jumlah_jp'];
            }

            $subject->classroomSubject()->attach($pivotData);
            $subjectTeacher = SubjectTeacher::firstOrNew(['user_id' => $validated['user_id']]);

            $subjectTeacher->total_jp = ($subjectTeacher->total_jp ?? 0) + $totalJpByClassrooms;;

            $subjectTeacher->save();

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

    public function checkBaseTeacherSubject($teacherId) {
        try {
            $teacherColour = TeacherColour::where('user_id', $teacherId)->with('teacher')->first();

            return response()->json([
                'message'   => 'get base colour teacher subject successfully',
                'data'      => $teacherColour
            ]);

        }catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    public function storeTeacherColour(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'user_id'   => ['required'],
                'colour'    => 'required',
                ], [
                'colour.required'   => 'Warna wajib dipilh'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();
            $teacherColour = TeacherColour::create([
                'user_id'       =>$validated['user_id'],
                'colour'        => $validated['colour']
            ]);

            $dataPassing = TeacherColour::where('id', $teacherColour['id'])->with('teacher')->first();
            return response()->json([
                    'message'   => 'teacher colour created succesfully',
                    'data'      => $dataPassing
                ],  HttpCode::CREATED);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSubjectById(Subject $subject, $classroomId) {
        try {
            // mengecek subject sudah dipakai di scheduleSubjects / jadwal pelajaran
             if (Subject::where('id', $subject['id'])->whereHas('scheduleSubjects')->exists()) {
               throw new ConflictException('mapel sudah digunakan dalam jadwal', [
                    'schedule_id' => 'mapel sudah digunakan dalam jadwal'
                ]);
            }
            // mengambil seluruh total_jp dari subjectTeacher
            $subjectTeacher = SubjectTeacher::where('user_id', $subject['user_id'])->first();

            // mengambil classroom_subject
            $classroomSubject = DB::table('classroom_subject')->where('classroom_id', $classroomId)->where('subject_id', $subject['id'])->first();

            // Jika ada subjectTeacher -> update atau hapus
            if ($subjectTeacher && $classroomSubject) {
                $newTotalJp = $subjectTeacher->total_jp - $classroomSubject->jumlah_jp;
                $newTotalJp > 0
                    ? $subjectTeacher->update(['total_jp' => $newTotalJp])
                    : $subjectTeacher->delete();
            }

            // menghapus relasi subject di classroom
            $this->detachSubjectFromClassroom($classroomId, $subject->id);

            // menghapus subject bila subject dalam classroom tidak ada lagi
            if(! DB::table('classroom_subject')->where('subject_id', $subject['id'])->exists()) {
                $subject->delete();
            }

           return response()->json([
            'message'   => 'subject deleted sucessfully'
           ]);
        } catch(ConflictException $exception) {
            return $exception->render(request());
        }catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    private function detachSubjectFromClassroom($classroomId, $ubjectId = null) {
        DB::table('classroom_subject') ->where([
                ['classroom_id', '=', $classroomId],
                ['subject_id', '=', $ubjectId],
            ])->delete();
    }
}
