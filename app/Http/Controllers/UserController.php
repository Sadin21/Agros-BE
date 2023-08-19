<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request, $id) {

        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'password'  => 'required|string',
            'address'   => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully'
        ]);
    }
    
    public function me() {

        return response()->json([
            'user' => auth()->user(),
            'message' => 'User retrieved successfully'
        ]);

    }
}
