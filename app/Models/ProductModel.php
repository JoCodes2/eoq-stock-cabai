<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'product';
    protected $fillable = [
        'id',
        'product_name',
        'unit',
        'created_at',
        'updated_at',
    ];
}
