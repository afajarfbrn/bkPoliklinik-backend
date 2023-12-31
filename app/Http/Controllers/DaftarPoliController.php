<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DaftarPoliController extends Controller
{
    public function index()
    {
        $daftar_poli = DaftarPoli::with('pasien', 'jadwal_periksa')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data daftar poli',
            'data' => $daftar_poli
        ], 200);  
    }

    public function show($id)
    {
        $daftar_poli = DaftarPoli::with('pasien', 'jadwal_periksa')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data daftar poli',
            'data' => $daftar_poli
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pasien' => 'required|string',
            'id_jadwal' => 'required|string',
            'keluhan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Ambil nomor antrian terakhir untuk id_jadwal yang sama
        $lastQueueNumber = DaftarPoli::where('id_jadwal', $request->id_jadwal)->max('no_antrian');
        
        // Jika tidak ada nomor antrian untuk id_jadwal yang sama, nomor antrian kembali menjadi 1
        if (!$lastQueueNumber) {
            $nextQueueNumber = 1;
        } else {
            $nextQueueNumber = $lastQueueNumber + 1;
        }

        // Simpan data baru ke database dengan nomor antrian yang baru
        $daftar_poli = DaftarPoli::create(
            array_merge(
                $validator->validated(),
                [
                    'id' => Str::uuid(),
                    'no_antrian' => $nextQueueNumber
                ]
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $daftar_poli
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_pasien' => 'required|string',
            'id_jadwal' => 'required|string',
            'keluhan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $daftar_poli = DaftarPoli::find($id);

        if (!$daftar_poli) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $daftar_poli->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $daftar_poli
        ], 200);
    }

    public function destroy($id)
    {
        $daftar_poli = DaftarPoli::find($id);

        if (!$daftar_poli) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $daftar_poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ], 200);
    }


}
