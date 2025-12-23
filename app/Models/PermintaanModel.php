<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'permintaan';

    protected $fillable = [
        'id',
        'pengguna_id',
        'nomor_permintaan',
        'total_harga_nota',
        'status',
        'catatan',
        'diproses_pada',
        'selesai_pada',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    // Relasi ke daftar item cabai (Detail)
    public function items(): HasMany
    {
        return $this->hasMany(ItemPermintaanModel::class, 'permintaan_id');
    }

    // Relasi ke log stok keluar (untuk tracking inventory)
    public function stokKeluar(): HasMany
    {
        return $this->hasMany(StokKeluarModel::class, 'permintaan_id');
    }
}
