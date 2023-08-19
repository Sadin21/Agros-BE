<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Psy\Readline\Hoa\Console;

class CustomerController extends Controller
{
    public function __construct() {
        
        $this->middleware('auth:api', ['except' => ['query']]);

        $this->middleware('permission:QUERY')->only(['me']);
        $this->middleware('permission:UPDATE|DELETE')->only(['update', 'delete']);
        $this->middleware('permission:SELF-UPDATE')->only(['selfUpdate']);
        // $this->middleware(function ($request, $next) {

        //     $editedUserId = $request->route('id');
            
        //     if (Auth()->user()->getPermissionNames = 'UPDATE') {
        //         if (Auth()->user()->getRoleNames === 'Super Admin') {
        //             return $next($request);
        //         } else {
        //             if (Auth()->user()->id == $editedUserId) {
        //                 return $next($request);
        //             } else {
        //                 return response()->json([
        //                     'message' => 'Unauthorized'
        //                 ], 401);
        //             }
        //         }
        //     }

        // });

    }

    public function query() {

        $user = User::with('roles')->get();
        return response()->json([
            'user' => $user,
            'message' => 'User retrieved successfully'
        ], 200);

    }
    
    public function me() {

        return response()->json([
            'user' => auth()->user(),
            'message' => 'User retrieved successfully'
        ]);

    }

    public function selfUpdate(Request $request, $id) {

        $user = User::find($id);
        $userId = $user->id;

        if (Auth()->user()->id != $userId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        } else if ($userId === null) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

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
        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully'
        ]);
    }

    public function update(Request $request, $id) {

        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'password'  => 'required|string',
            'address'   => 'string',
            'role'      => 'string|exists:roles,name'
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
        $user->save();

        $user->assignRole($request->role);

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully'
        ]);
    }
    
    public function delete($id) {
            
            $user = User::find($id);
    
            if ($user === null) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
    
            $user->delete();
    
            return response()->json([
                'message' => 'User deleted successfully'
            ]);
    }
}
