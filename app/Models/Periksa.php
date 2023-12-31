<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    use HasFactory;

    protected $table = 'periksa';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_daftar_poli',
        'tanggal',
        'catatan',
        'biaya_periksa',
    ];

    public function daftar_poli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli', 'id');
    }
}
