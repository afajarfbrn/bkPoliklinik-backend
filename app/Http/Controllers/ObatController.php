<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data obat',
            'data' => $obat
        ], 200);
    }

    public function show($id)
    {
        $obat = Obat::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data obat',
            'data' => $obat
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required',
            'kemasan' => 'required',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $obat = Obat::create(array_merge(
            $validator->validated(), 
            ['id' => Str::uuid()]
        ));

        if ($obat) {
            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil ditambahkan',
                'data' => $obat
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Obat gagal ditambahkan',
        ], 409);
    }

    public function update(Request $request, $id)
    {
        $obat = Obat::find($id);

        if ($obat) {
            $validator = Validator::make($request->all(), [
                'nama_obat' => 'required',
                'kemasan' => 'required',
                'harga' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $obat->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil diupdate',
                'data' => $obat
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Obat tidak ditemukan',
        ], 404);
    }

    public function destroy($id)
    {
        $obat = Obat::find($id);

        if ($obat) {
            $obat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Obat tidak ditemukan',
        ], 404);
    }
    
}
