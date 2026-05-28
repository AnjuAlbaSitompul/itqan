<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    protected $fillable = [
        'name',
    ];

    public function menus()
    {
        return $this->belongsToMany(
            Menu::class,
            'menu_roles',
            'role_id',
            'menu_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'outlet_user',
            'outlet_id',
            'user_id'
        );
    }


}
