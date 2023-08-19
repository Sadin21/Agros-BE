<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct() {
        
        $this->middleware('auth:api', ['except' => ['login']]);

        // $this->middleware(['role:Admin', 'permission:USER.QUERY|USER.REGISTER'])->except(['query', 'login', 'logout', 'refresh']);
        // $this->middleware(['role:User', 'permission:USER.QUERY'])->except(['query', 'login', 'logout', 'refresh', 'register']);
        $this->middleware('permission:QUERY')->only(['logout', 'refresh']);
        $this->middleware('permission:REGISTER')->only(['register']);

    }

    public function login(Request $request) {

        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string'
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'errors' => 'invalid username and password'
            ], 401);
        }

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        $roles = $user->getRoleNames();

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'roles' => $roles,
            'authorization' => [
                'token' => $token,
                'type' => 'Bearer'
            ]
        ]);

        return response()->json([
            'user' => auth('api')->user(),
            'authorization' => [
                'token' => $token,
                'type' => 'Bearer'
            ]
        ]);

    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
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

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'address'   => $request->address
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);

    }

    public function logout() {

        Auth::logout();
        return response()->json([
            'message' => 'User logged out successfully'
        ]);

    }

    public function refresh() {

        return response()->json([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'Bearer'
            ]
        ]);

    }
}
