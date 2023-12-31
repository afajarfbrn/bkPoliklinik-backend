<?php

namespace App\Http\Controllers;

use App\Models\DetailPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DetailPeriksaController extends Controller
{
    public function index()
    {
        $detail_periksa = DetailPeriksa::with('periksa', 'obat')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data detail_periksa',
            'data' => $detail_periksa
        ], 200);
    }

    public function showByPeriksaId($id)
    {
        $detail_periksa = DetailPeriksa::with('periksa', 'obat')->where('id_periksa', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data detail_periksa',
            'data' => $detail_periksa
        ], 200);
    }

    public function show($id)
    {
        $detail_periksa = DetailPeriksa::with('periksa', 'obat')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data detail_periksa',
            'data' => $detail_periksa
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_periksa' => 'required|string',
            'id_obat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detail_periksa = DetailPeriksa::create(
            array_merge(
                $validator->validated(),
                [
                    'id' => Str::uuid(),
                ]
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil disimpan',
            'data' => $detail_periksa
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_periksa' => 'required|string',
            'id_obat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detail_periksa = DetailPeriksa::findOrFail($id);

        if (!$detail_periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail_periksa tidak ditemukan',
            ], 404);
        }

        $detail_periksa->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil diubah',
            'data' => $detail_periksa
        ], 200);
    }

    public function destroy($id)
    {
        $detail_periksa = DetailPeriksa::find($id);

        if (!$detail_periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail_periksa tidak ditemukan',
            ], 404);
        }

        $detail_periksa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil dihapus',
        ], 200);
    }

    public function destroyByObatId($id, $idPeriksa)
    {
       $detail_periksa = DetailPeriksa::where('id_obat', $id)->where('id_periksa', $idPeriksa)->first();

        if (!$detail_periksa) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail_periksa tidak ditemukan',
            ], 404);
        }

        $detail_periksa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data detail_periksa berhasil dihapus',
        ], 200);
    }   


}
