<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestOilModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'requests';
    protected $fillable = [
        'id',
        'market_id',
        'product_id',
        'request_date',
        'end_time',
        'description',
        'status_request',
        'selected_supplier_id',
        'created_at',
        'updated_at',

    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'selected_supplier_id');
    }
    public function market(): BelongsTo
    {
        return $this->belongsTo(User::class, 'market_id');
    }
    public function supllyOffers(): HasMany
    {
        return $this->hasMany(RequestSupplyModel::class, 'request_id');
    }
}
