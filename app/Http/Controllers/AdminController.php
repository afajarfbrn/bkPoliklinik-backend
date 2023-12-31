<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'store']]);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'name'      => 'required',
            'username'  => 'required:unique:admin',
            'role'      => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $admin = Admin::create(
            array_merge(
                $validator->validated(),
                [
                    'id' => Str::uuid(),
                    'password' => bcrypt($request->password),
                ]
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Data admin berhasil ditambahkan',
        ], 201);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$token = auth()->guard('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Username atau password salah.'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        auth()->guard('admin')->user();

        if (!$admin = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['Unauthorized'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data admin.',
            'data' => $admin
        ]);
    }


    public function logout()
    {
        auth()->guard('admin')->logout();

        return response()->json(['message' => 'Berhasil logout.']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    } 
}
