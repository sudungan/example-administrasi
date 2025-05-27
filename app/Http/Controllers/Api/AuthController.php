<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $validated = $request->validate([
                'email' => 'required',
                'password'  => 'required'
            ], [
                'required.email'    => 'masukkan email',
                'required.password' => 'masukkan password'
            ]);
            return response()->json([
                'message'   => 'data ada'
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function register(Request $request) {
        try {
            $validated  = $request->validate([
                'name'      => 'required',
                'email'     => 'required',
                'password'  => 'required'
            ]);

            return response()->json([
                'message'   => 'data ada'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage()
            ], 500);
        }
    }
}
