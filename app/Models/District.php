<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'average_sales',
        'market_size',
        'market_share',
        'competition_brand',
        'total_outlets',
        'own_outlets',
        'coverage',
        'longitude',
        'latitude',
        'location',
        'district',
    ];
}
