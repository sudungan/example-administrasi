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
            'listUser'  => User::with('role')->get(),
            'listRole'  => MainRole::mainRole,
        ]);
    }

    public function getAllUser(Request $request) {
        try {
            $users = User::with('role')->get();
            return response()->json([
                'data'     => $users, 
                'message'  => 'get all data successfully'
            ]);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
