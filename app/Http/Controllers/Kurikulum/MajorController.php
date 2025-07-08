<?php

namespace App\Http\Controllers\Kurikulum;

use Illuminate\Http\Request;
use App\Models\{AdditionRole, Major, User};
use App\Http\Controllers\Controller;
use App\Helpers\{HttpCode, MainRole};
use Illuminate\Support\Facades\Validator;
use App\Exceptions\{ConflictException, NotFoundException};
use Carbon\Carbon;
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

    public function getHeadMajorById() {
         try {
            $headMajorById = AdditionRole::where('slug', 'kepala-jurusan')->value('id');
                if (!$headMajorById) {
                    throw new NotFoundException(
                    'Kepala Jurusan belum tersedia',
                    ['addition_role_id' => ['Jabatan Kepala Jurusan belum tersedia.']],
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
            ]);
        }
    }

    public function storeMajor(Request $request) {
        try {
             $validator = Validator::make($request->all(), [
                'user_id'                   => ['required', 'unique:' . Major::class],
                'addition_role_id'          => 'required',
                'name'                      => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/','unique:' . Major::class],
            ], [
                'name.unique'           => 'Nama Jurusan sudah digunakan..',
                'name.min'              => 'Nama user minimal 3 karakter',
                'name.regex'            => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'user_id.required'      => 'Nama Kanidat wajib dipilih',
                'user_id.unique'        => 'Nama Kanidat wajib dipakai',
                'addition_role_id'      => 'Jabatan Kepala Jurusan Belum ada',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validated();

            $major = Major::create([
                'name'      => $validated['name'],
                'user_id'   => $validated['user_id'],
                'initial'   => $this->generateInitial($validated['name']),
                'slug'      => $this->generateSlug($validated['name']),
            ]);

            // melakukan create many to many melalui relasi additionRoles
            $this->attachHeadMajorRelation($major);

            return response()->json([
                'message'   => 'Major created successfully',
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

    public function updateMajor(Request $request, Major $major) {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'name' => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],
            ], [
                'name.unique'  => 'Nama Jurusan sudah digunakan..',
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'user_id.required'=> 'Nama Kepala Jurusan wajib dipilih',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();

            $major->update([
                'name'  => $validated['name'],
                'user_id'   => $validated['user_id'],
                'initial'   => $this->generateInitial($validated['name']),
                'slug'      => $this->generateSlug($validated['name']),
            ]);

            $this->syncHeadMajorRelation($major);

            return response()->json([
                'message'   => 'Major updated successfully..'
            ], HttpCode::OK);

        } catch (\Exception $error) {
            return response()->json([
                'message'  => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMajor(Major $major) {
        try {
            if (Major::where('id', $major['id'])->whereHas('classrooms')->exists()) {
                throw new ConflictException('jurusan sudah digunakan', [
                    'major_id' => 'Tidak dapat dihapus karena masih digunakan'
                ]);
            }
            $this->detachRelationMajor($major);

            $major->delete();

            return response()->json([
                'message'   => 'Major deleted successfully'
            ], HttpCode::OK);

        } catch(ConflictException $exception) {
            return $exception->render(request());
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    private function generateInitial(string $text) {
        return Str::of($text)
            ->explode(' ')
            ->filter(fn ($word) => !in_array(Str::lower($word), ['dan', 'dari', 'ke', 'di']))
            ->map(fn (string $name)=> Str::of($name)->substr(0,1))->implode('');
    }

    private function generateSlug(string $text) {
        return Str::slug($text, '-');
    }

    private function getHeadMajor() {
        return AdditionRole::where('slug', 'kepala-jurusan')->value('id');
    }

    private function syncHeadMajorRelation($major) {
        DB::table('addition_role_user')->where('reference_id', $major['id'])->update([
            'addition_role_id'  => $this->getHeadMajor(),
            'user_id'  => $major['user_id'],
            'reference_table'  => $major->getTable(),
            'reference_id'  => $major['id'],
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }

    private function attachHeadMajorRelation($major) {
        DB::table('addition_role_user')->insert([
            'addition_role_id'  => $this->getHeadMajor(),
            'user_id'  => $major['user_id'],
            'reference_table'  => $major->getTable(),
            'reference_id'  => $major['id'],
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }

    private function detachRelationMajor($major) {
        DB::table('addition_role_user')
            ->where([
                ['user_id', '=', $major['user_id']],
                ['addition_role_id', '=', $this->getHeadMajor()],
            ])
            ->orWhere('reference_id', $major['id'])->delete();
    }
}
