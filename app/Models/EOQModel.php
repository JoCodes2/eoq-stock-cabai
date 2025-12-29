<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EOQModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pengaturan_eoq';
    protected $fillable = [
        'id',
        'master_data_id',
        'permintaan_tahunan',
        'biaya_pemesanan',
        'biaya_penyimpanan',
        'waktu_tunggu_hari',
        'stok_aman',
        'nilai_eoq',
        'titik_pemesanan_ulang',
        'terakhir_dihitung',
        'aktif'
    ];

    // Relasi ke Master Data (Cabai)
    public function masterData(): BelongsTo
    {
        return $this->belongsTo(MasterModel::class, 'master_data_id');
    }

    protected $appends = ['status_stok'];

    public function getStatusStokAttribute()
    {
        $stokSekarang = $this->masterData->jumlah;
        if ($stokSekarang <= $this->stok_aman) return 'KRITIS';
        if ($stokSekarang <= $this->titik_pemesanan_ulang) return 'REORDER';
        return 'AMAN';
    }
}
