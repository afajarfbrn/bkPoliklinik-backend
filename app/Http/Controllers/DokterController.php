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
        $this->middleware('auth:dokter', ['except' => ['login', 'store', 'index', 'show', 'update', 'destroy']]);
    }

    public function index()
    {
        $dokter = Dokter::with('poli')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data dokter',
            'data' => $dokter
        ], 200);
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

    public function show($id)
    {
        $dokter = Dokter::with('poli')->where('id', $id)->first();

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data dokter',
            'data' => $dokter
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'alamat'    => 'required',
            'no_hp'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dokter->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data dokter berhasil diubah',
            'data' => $dokter
        ], 200);
    }

    public function destroy($id)
    {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter tidak ditemukan',
            ], 404);
        }

        $dokter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data dokter berhasil dihapus',
        ], 200);
    }

}
