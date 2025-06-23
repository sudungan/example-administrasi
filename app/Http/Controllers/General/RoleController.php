<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index() {
        return view('roles.index');
    }

    public function getListRole() {
        try {
            $listRole = Role::with('additionRole')->get();
            // dd($listRole);
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
}
