<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pasien', ['except' => ['login', 'register', 'index', 'show', 'update', 'destroy']]);
    }

    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'username'  => 'required|unique:pasien', // Perhatikan penambahan 'unique:pasien'
            'alamat'    => 'required',
            'no_hp'     => 'required',
            'no_ktp'    => 'required',
            'password'  => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Menghitung jumlah pasien yang sudah terdaftar
        $jumlahPasien = Pasien::count();
    
        // Membuat nomor RM dengan format YYYYMM-XXX
        $nomorRM = date('Ym') . '-' . sprintf('%03d', $jumlahPasien + 1);
    
        $pasien = Pasien::create(
            array_merge(
                $validator->validated(),
                [
                    'id' => Str::uuid(),
                    'no_rm' => $nomorRM,
                    'password' => bcrypt($request->password),
                ]
            )
        );
    
        return response()->json([
            'success' => true,
            'message' => 'Data pasien berhasil ditambahkan',
            'no_rm' => $nomorRM, // Mengirim nomor RM sebagai respons
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$token = auth()->guard('pasien')->attempt($credentials)) {
            return response()->json(['error' => 'Username atau password salah.'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        auth()->guard('pasien')->user();

        if (!$pasien = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['Unauthorized'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data pasien.',
            'data' => $pasien
        ]);
    }

    public function logout()
    {
        auth()->guard('pasien')->logout();

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

    public function index()
    {
        $pasien = Pasien::orderBy('created_at', 'ASC')->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data pasien.',
            'data' => $pasien
        ]);
    }

    public function show($id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien dengan id ' . $id . ' tidak ditemukan'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menampilkan data pasien dengan id ' . $id,
            'data' => $pasien
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien dengan id ' . $id . ' tidak ditemukan'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'username'  => 'required|unique:pasien,username,' . $id, // Perhatikan penambahan 'unique:pasien,username,' . $id
            'alamat'    => 'required',
            'no_hp'     => 'required',
            'no_ktp'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pasien->update($request->all());

        if ($pasien) {
            return response()->json([
                'success' => true,
                'message' => 'Data pasien dengan id ' . $id . ' berhasil diupdate',
                'data' => $pasien
            ], 200);
        }
    }

    public function destroy($id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien dengan id ' . $id . ' tidak ditemukan'
            ], 400);
        }

        if ($pasien->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pasien dengan id ' . $id . ' berhasil dihapus',
            ], 200);
        }
    }


}
