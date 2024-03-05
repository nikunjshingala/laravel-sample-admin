<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceList extends Model
{
    use HasFactory;
    protected $table="user_device_list";
    protected $fillable = [
        'user_id',
        'api_level',
        'brand',
        'build_number',
        'device_country',
        'device_name',
        'manufacturer',
        'model',
        'system_name',
        'system_version',
        'version',
        'device_token',
    ];  
}
