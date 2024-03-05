<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use HasFactory;
    protected $table = 'menu_modules';
    /**
     * Get Menu Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function Permission()
    {
        return $this->hasMany('App\Permission','i_employee_group_id');
    }
}
