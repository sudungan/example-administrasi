<?php

namespace App\Http\Controllers\General;

use App\Helpers\MainRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{AdditionRole, Role, User};
use Illuminate\Validation\{ValidationException, Rules};
use Illuminate\Support\Facades\{Hash, Validator};
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{

    public function index() {
        return view('users.index', [
            'listRole'  => MainRole::item,
        ]);
    }

    public function searchUser() {
        try {
            $searchUser = request('search');

            $user = User::when($searchUser, function($query) use($searchUser){
                $query->where('name', 'like', '%' . $searchUser . '%')
                ->orWhereHas('role', function($query) use($searchUser){
                     $query->where('name', 'like', '%' . $searchUser . '%');
                });
            })->orderBy('id', 'desc')->with(['role'])->paginate(5);

            return response()->json([
                'data'  => $user,
                'message'  => 'get data successfully'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage()
            ]);
        }
    }

    public function getListUser() {
        try {
            $users = User::with(['role'])->paginate(5);

            return response()->json([
                'message'  => 'get all data successfully',
                'data'     => $users,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'mesaage'   => $th->getMessage()
            ]);
        }
    }

    public function showUser(Request $request, $userId) {
       try {
           $user = User::with(['role', 'detailUser'])->where('id', $userId)->first();
           return response()->json([
            'message'   => 'get user succesfully',
            'data'      => $user
           ]);
       } catch (\Throwable $th) {
        return response()->json([
            'message'   => $th->getMessage()
        ]);
       }
    }

    public function storeDataUserGeneral(Request $request) {
        try {
             $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', 'min:3'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'min:8'],
                'role_id' => 'required',
            ], [
                'email.unique'  => 'Email sudah digunakan..',
                'name.min'  => 'Nama user minimal 3 karakter',
                'password.string'  => 'tet',
                'password.min'  => 'Password minimal 8 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $validated = $validator->validated();
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);
            $dataUser = [
                'role_id'   => $user->role_id,
                'user_id'   => $user->id
            ];

             return response()->json([
                'message'   => 'User created successfully',
                'data'      =>  $dataUser
             ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], 500);
        }
    }

    public function getListRole(Request $request) {
        try {
            $rolesUsed = User::whereHas('role', function ($query) {
                $query->whereIn('role_id', [
                    MainRole::item['admin'],
                    MainRole::item['kepala-sekolah']
                ]);
            })->pluck('role_id')->unique()->toArray();

            $roles = count($rolesUsed) > 0
                ? Role::whereNotIn('id', $rolesUsed)->select('id','name')->get()
                : Role::select('id','name')->get();
            $listRole = $roles->toArray();

            return response()->json([
                'message'   => 'get List Role Successfuly',
                'data'      => $listRole
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

    public function getAdditionRole(Request $request, $roleId) {
        try {
            $additionRole = AdditionRole::where('role_id', $roleId)->get();

            return response()->json([
                'message'   => 'get all addition role succesfully',
                'data'      => $additionRole
            ]);
        } catch (\Exception $error) {
           return response()->json([
            'message'   => $error->getMessage()
           ]);
        }
    }

    public function getSelectedRole(Request $request, $roleId) {
        try {
            $additionRole = AdditionRole::where('role_id', $roleId)->get();

            return response()->json([
                'data'  => $additionRole,
                'message'   => 'addition role get succesfully'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
    }

}
