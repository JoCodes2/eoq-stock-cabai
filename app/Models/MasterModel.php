<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'master_data';
    protected $fillable = [
        'id',
        'kode',
        'nama',
        'satuan',
        'jumlah',
        'stok_minimum',
        'harga_beli_terakhir',
        'harga_jual',
        'is_aktif',
        'created_at',
        'updated_at',
    ];

    public function stokMasuk(): HasMany
    {
        return $this->hasMany(StokmasukModel::class, 'master_data_id');
    }
}
