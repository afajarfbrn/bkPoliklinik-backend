<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPeriksa extends Model
{
    use HasFactory;

    protected $table = 'jadwal_periksa';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'id',
        'id_dokter',
        'hari',
        'status',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id');
    }


}
