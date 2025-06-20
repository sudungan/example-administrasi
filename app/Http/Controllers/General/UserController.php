<?php

namespace App\Http\Controllers\General;

use App\Helpers\MainRole;
use App\Http\Controllers\Controller;
use App\Models\{AdditionRole, User,};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function index() {
        return view('users.index', [
            'listRole'  => MainRole::mainRole,
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
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', Rules\Password::defaults()],
                'role_id'   => 'required',
            ]);

             $validated['password'] = Hash::make($validated['password']);
             $user = User::create($validated);

            $dataUser = [
                'role_id'   => $user->role_id,
                'user_id'   => $user->id
            ];

             return response()->json([
                'message'   => 'data user general created succesfully',
                'data'      =>  $dataUser
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
