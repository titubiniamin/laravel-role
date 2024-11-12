<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Highwall extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'brand',
        'longitude',
        'latitude',
        'location',
        'district'
    ];
}
