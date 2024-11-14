<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'type',
        'brand',
        'longitude',
        'latitude',
        'location',
        'district',
        'image',
        'start_date',
        'end_date',
    ];
}
