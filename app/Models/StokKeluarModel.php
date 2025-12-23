<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokKeluarModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'stok_keluar';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'master_data_id',
        'permintaan_id',
        'jumlah',
        'keterangan',
        'dikeluarkan_pada',
    ];

    public function masterData(): BelongsTo
    {
        return $this->belongsTo(MasterModel::class, 'master_data_id');
    }

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanModel::class, 'permintaan_id');
    }
}
