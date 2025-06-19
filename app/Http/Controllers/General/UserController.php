<?php

namespace App\Http\Controllers\General;

use App\Helpers\MainRole;
use App\Http\Controllers\Controller;
use App\Models\User;
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
                'data'     => $users,
                'message'  => 'get all data successfully'
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
             return response()->json([
                'message'   => 'data user general created succesfully'
             ]);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ]);
        }
     }
}
