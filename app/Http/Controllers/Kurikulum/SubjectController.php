<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\{TeacherColour, Classroom, Subject, User};
use App\Exceptions\{ConflictException, NotFoundException};
use App\Helpers\MainRole;
use Illuminate\Support\Facades\DB;

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
                'user_id'   => ['required', 'unique:' . Subject::class],
                'classroom_id'  => 'required',
                'jumlah_jp'  => 'required',
                'colour'    => 'required'
                ], [
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'user_id.unique'  => 'Nama guru sudah digunakan dalam pelajaran ini..',
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

            TeacherColour::create([
                'user_id'   =>$validated['user_id'],
                'subject_id'    => $subject->id,
                'colour'        => $validated['colour']
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

    public function checkBaseTeacherSubject() {
        try {
            $listTeacherColour = TeacherColour::with('teacher')
                                ->whereIn('user_id', DB::table('users')->select('id')->where('role_id', MainRole::item['guru'])->pluck('id'))
                                ->get();

            if ($listTeacherColour->isEmpty()) {
                 throw new NotFoundException(
                    'list Base Colour Teacher belum ada.',
                    ['teacher_id' => ['list Base Colour Teacher belum ada.']
                    ], HttpCode::NOT_FOUND
                );
            }

            return response()->json([
                'message'   => 'get data base teacher colour successfully',
                'data'      => $listTeacherColour
            ], HttpCode::OK);
        }  catch(NotFoundException $error) {
            return $error->render(request());
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
                'subject_id'  => 'nullable',
                'colour'    => 'required'
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
                'user_id'   =>$validated['user_id'],
                'subject_id'    => null,
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
}
