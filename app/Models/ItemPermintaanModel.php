<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPermintaanModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'item_permintaan';

    protected $fillable = [
        'id',
        'permintaan_id',
        'master_data_id',
        'nama_cabai',
        'satuan',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanModel::class, 'permintaan_id');
    }

    public function masterData(): BelongsTo
    {
        return $this->belongsTo(MasterModel::class, 'master_data_id');
    }
}
