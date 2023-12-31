<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Dokter;
use Illuminate\Support\Facades\Auth;

class DokterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:dokter', ['except' => ['login', 'store']]);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'nama'      => 'required',
            'username'  => 'required:unique:dokter',
            'alamat'    => 'required',
            'no_hp'     => 'required',
            'role'      => 'required',
            'password'  => 'required',
            'id_poli'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dokter = Dokter::create(
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
            'message' => 'Data dokter berhasil ditambahkan',
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$token = auth()->guard('dokter')->attempt($credentials)) {
            return response()->json(['error' => 'Username atau password salah.'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->guard('dokter')->user());
    }

    public function logout()
    {
        auth()->guard('dokter')->logout();

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
