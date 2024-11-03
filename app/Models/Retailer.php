<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'owner_name',
        'zone',
        'retailer_code',
        'email',
        'website',
        'mobile',
        'address',
        'longitude',
        'latitude',
        'location',
        'average_sales',
        'market_size',
        'market_share',
        'competition_brand'
    ];

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }
}
