<?php

namespace App\Http\Controllers\General;

use App\Helpers\MainRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index() {
        return view('users.index', [
            'listUser'  => User::with('role')->paginate(5),
            'listRole'  => MainRole::mainRole,
        ]);
    }

    public function getAllUser(Request $request) {
        try {
            $users = User::with('role')->paginate(5);
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
}
