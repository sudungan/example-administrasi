<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\AdditionRole;
use Illuminate\Http\Request;
use App\Models\{Role, User};
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
                'name' => ['required', 'string', 'max:255', 'min:3'],
                'role_id' => 'required',
            ], [
                'name.min'  => 'Nama user minimal 3 karakter',
                'role_id.string'  => 'tet',
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
                'message'   => 'created addition-role successfully'
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
        $additionRole = AdditionRole::findOrFail($roleId);
    }
}
