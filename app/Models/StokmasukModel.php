<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StokmasukModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'stok_masuk';
    protected $fillable = [
        'id',
        'kode_stok_masuk',
        'master_data_id',
        'jumlah',
        'harga_beli_satuan',
        'nama_supplier',
        'total_harga',
        'no_invoice',
        'created_at',
        'updated_at',

    ];


    public function masterdata(): BelongsTo
    {
        return $this->belongsTo(MasterModel::class, 'master_data_id');
    }
}
