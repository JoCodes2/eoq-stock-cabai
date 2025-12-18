<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestSupplyModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'supplier_offers';
    protected $fillable = ['id', 'supplier_id', 'request_id', 'status', 'created_at', 'updated_at'];
    public function request(): BelongsTo
    {
        return $this->belongsTo(RequestOilModel::class, 'request_id', 'id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }
}
