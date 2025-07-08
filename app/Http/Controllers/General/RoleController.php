<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Role, User, AdditionRole};
use App\Exceptions\ConflictException;
use Illuminate\Support\Facades\{Hash, Validator};
use App\Helpers\{HttpCode, MainRole};
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index() {
        return view('roles.index');
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z\s]+$/', 'min:3', 'max:255', 'unique:' . AdditionRole::class],
                'role_id' => 'required',
            ], [
                'name.required'  => 'Nama Jabatan tambahan harus diisi..',
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.unique'  => 'Nama Jabatan sudah dipakai',
                'name.regex'   => 'Nama Jabatan hanya boleh berisi huruf dan spasi.',
                'role_id.required'  => 'Jabatan utama wajib dipilih..',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validated();
            AdditionRole::create([
                'name'      => $validated['name'],
                'role_id'   => $validated['role_id'],
                'slug'      => Str::slug($validated['name'])
            ]);

            return response()->json([
                'message'   => 'Addition role created.'
            ], HttpCode::CREATED);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    public function getListRole() {
        try {

            $listRole = Role::with('additionRole')->get();
            return response()->json([
                'message'   => 'get list role successfully',
                'data'      => $listRole
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    public function deleteAdditionRole($additionRoleId) {
        try {
            // user memiliki role tambahan
            $usersHasAdditionRole = User::whereHas('additionRoles', function($query)use($additionRoleId) {
                $query->where('addition_role_id', $additionRoleId);
            })->count();

            if ($usersHasAdditionRole) {
               throw new ConflictException('jabatan tambahan sedang digunakan', [
                'addition_role_id'  => 'Tidak dapat dihapus karena masih digunakan'
               ]);
            }

            $additionRole = AdditionRole::findOrFail($additionRoleId);
            $additionRole->delete();
            return response()->json([
                'message'   => 'addition role berhasil dihapus'
            ], HttpCode::OK);

        } catch(ConflictException $exception) {
             return $exception->render(request());
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function editAdditionRole($additionRoleId) {
        try {
            $additionRole = AdditionRole::select('id', 'role_id', 'name')->with('role:id,name')->findOrFail($additionRoleId);

            return response()->json([
                'message' => 'get addition role successfuly',
                'data'  => $additionRole
            ], HttpCode::OK);

        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }

    }

    public function updateAdditionRole(Request $request, $additionRoleId) {
        try {
             $validator = Validator::make($request->all(), [
                'role_id' => 'required',
                'name' => ['required', 'string', 'max:255', 'min:3', 'max:255',  'regex:/^[a-zA-Z\s]+$/', 'unique:' . AdditionRole::class],
            ], [
                'name.required'  => 'Nama Jabatan tambahan harus diisi..',
                'name.string'   => 'string woi',
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.unique'  => 'Nama Jabatan sudah dipakai',
                'name.regex'    => 'Nama Jabatan hanya boleh berisi huruf dan spasi.',
                'role_id.required'  => 'Jabatan utama wajib dipilih..',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $usersHasAdditionRole = User::whereHas('additionRoles', function($query)use($additionRoleId) {
                $query->where('addition_role_id', $additionRoleId);
            })->count();

            if ($usersHasAdditionRole) {
               throw new ConflictException('jabatan tambahan sedang digunakan', [
                'addition_role_id'  => 'Tidak dapat dihapus karena masih digunakan'
               ]);
            }

            $validated = $validator->validated();
            $additionRole = AdditionRole::findOrFail($additionRoleId);
            $additionRole->update([
                'role_id'   => $validated['role_id'],
                'name'      => $validated['name'],
                'slug'      => Str::slug($validated['name'])
            ]);

            return response()->json([
                'message'   => 'Jabatan berhasil diupdate'
            ], HttpCode::OK);
        } catch(ConflictException $exception) {
             return $exception->render(request());
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
