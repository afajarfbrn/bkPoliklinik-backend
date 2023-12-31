<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PoliController extends Controller
{
    public function index()
    {
        $poli = Poli::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data poli',
            'data' => $poli
        ], 200);  
    }

    public function show($id)
    {
        $poli = Poli::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data poli',
            'data' => $poli
        ], 200);
    }

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'nama_poli' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $poli = Poli::create(
            array_merge(
                $validator->validated(),
                ['id' => Str::uuid()]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Poli berhasil disimpan',
            'data' => $poli
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $poli = Poli::find($id);

        if (!$poli) {
            return response()->json([
                'success' => false,
                'message' => 'Poli tidak ditemukan',
            ], 404);    
        }

        $poli->fill([
            'nama_poli' => $request->nama_poli,
            'keterangan' => $request->keterangan,
        ]);

        $poli->save();

        return response()->json([
            'success' => true,
            'message' => 'Poli berhasil diupdate',
            'data' => $poli
        ], 200);
    }

    public function destroy($id)
    {
        $poli = Poli::find($id);

        if (!$poli) {
            return response()->json([
                'success' => false,
                'message' => 'Poli tidak ditemukan',
            ], 404);    
        }

        $poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'Poli berhasil dihapus',
        ], 200);
    }
}
