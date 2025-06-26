<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Role, User, AdditionRole};
use App\Exceptions\ForbiddenTransactionException;
use Illuminate\Support\Facades\{Hash, Validator};
use App\Helpers\{HttpCode, MainRole};
class RoleController extends Controller
{
    public function index() {
        return view('roles.index');
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', 'min:3', 'max:255', 'unique:' . AdditionRole::class],
                'role_id' => 'required',
            ], [
                'name.required'  => 'Nama Jabatan tambahan harus diisi..',
                'name.min'  => 'Nama user minimal 3 karakter',
                'name.unique'  => 'Nama Jabatan sudah dipakai',
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
                'name'  => $validated['name'],
                'role_id'   => $validated['role_id']
            ]);

            return response()->json([
                'message'   => 'Addition role created.'
            ]);
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

    public function deleteAdditionRole($roleId) {
        try {
            $additionRole = AdditionRole::findOrFail($roleId);
            $additionRole->delete();
            return response()->json([
                'message'   => 'jabatan tambahan berhasil dihapus'
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }
}
