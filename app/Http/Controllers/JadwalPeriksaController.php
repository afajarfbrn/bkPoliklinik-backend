<?php

namespace App\Http\Controllers;

use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $jadwal_periksa = JadwalPeriksa::with('dokter.poli')->get();
        

        return response()->json([
            'success' => true,
            'message' => 'Daftar data jadwal periksa',
            'data' => $jadwal_periksa
        ], 200);  
    }

    public function show($id)
    {
        $jadwal_periksa = JadwalPeriksa::with('dokter')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data jadwal periksa',
            'data' => $jadwal_periksa
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_dokter' => 'required|string',
            'hari' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jadwal_periksa = JadwalPeriksa::create(
            array_merge(
                $validator->validated(),
                ['id' => Str::uuid()]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Jadwal periksa berhasil disimpan',
            'data' => $jadwal_periksa
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_dokter' => 'required|string',
            'hari' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jadwal_periksa = JadwalPeriksa::findOrFail($id);

        if ($jadwal_periksa) {
            $jadwal_periksa->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Jadwal periksa berhasil diubah',
                'data' => $jadwal_periksa
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Jadwal periksa tidak ditemukan',
        ], 404);
    }

    public function destroy($id)
    {
        $jadwal_periksa = JadwalPeriksa::find($id);

        if ($jadwal_periksa) {
            $jadwal_periksa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal periksa berhasil dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Jadwal periksa tidak ditemukan',
        ], 404);
    }
}
