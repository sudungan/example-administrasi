<?php

namespace App\Http\Controllers\Kurikulum;

use Illuminate\Http\Request;
use App\Models\{AdditionRole, Major, User};
use App\Http\Controllers\Controller;
use App\Helpers\{HttpCode, MainRole};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MajorController extends Controller
{
    public function index() {
        // return view('majors.with-alpine.index');
        return view('majors.with-vue.index');
    }

    public function getListMajor() {
        try {
            $majors = Major::with('headMajor:id,name')->get();
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

    public function getListTeacher() {
        try {

            $teachers = User::select('id', 'name')->where('role_id', MainRole::item['guru'])->get();
            return response()->json([
                'message'   => 'get List Teachers successfully',
                'data'      => $teachers
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    public function storeMajor(Request $request) {
        try {
             $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'unique:' . Major::class],
                'name' => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/','unique:' . Major::class],
            ], [
                'name.unique'  => 'Nama Jurusan sudah digunakan..',
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'user_id.required'=> 'Nama Kanidat wajib dipilih',
                'user_id.unique'=> 'Nama Kanidat wajib dipakai'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validated();

            // membuat slug
            $slug = Str::slug($validated['name'], '-');

            // membuat inisial nama jurusan
            $initial = Str::of($validated['name'])
                        ->explode(' ')
                        ->filter(fn ($word) => !in_array(Str::lower($word), ['dan', 'dari', 'ke', 'di']))
                        ->map(fn (string $name)=> Str::of($name)->substr(0,1))->implode('');

            // mengambil data kepala jurusan berbentuk objek
            $kepalaAJurusan = additionRole::query()->select('id')->where('name', 'kepala jurusan')->firstOrFail();

            Major::create([
                'name'      => $validated['name'],
                'user_id'   => $validated['user_id'],
                'initial'   => $initial,
                'slug'      => $slug,
            ]);

            $user = User::findOrFail($validated['user_id']); // mengambil object dari user_id

            // melakukan create many to many melalui relasi additionRoles
            $user->additionRoles()->attach($kepalaAJurusan['id']);

            return response()->json([
                'message'   => 'User created successfully',
             ], HttpCode::CREATED);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getMajorBy($majorId) {
        try {
            $major = Major::select('id', 'name', 'slug', 'user_id', 'initial')->with('headMajor:id,name')->findOrFail($majorId);
            return response()->json([
                'message'   => 'get major successfully',
                'data'      => $major,
           ]);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateMajor(Request $request, $majorId) {
        try {
            dd($request);
            return response()->json([
                'message'   => ''
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'  => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMajor($majorId) {
        try {
            $major = Major::with('headMajor')->findOrFail($majorId);

            return response()->json([
                'message'   => 'delete major successfully'
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
