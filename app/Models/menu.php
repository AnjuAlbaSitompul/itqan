<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'route',
        'parent_id',
        'sort',
        'is_active'
    ];

    /*
    |--------------------------------------------------------------------------
    | CHILDREN
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(
            menu::class,
            'parent_id'
        )->orderBy('sort');
    }

    /*
    |--------------------------------------------------------------------------
    | PARENT
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(
            menu::class,
            'parent_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        return $this->belongsToMany(
            roles::class,
            'menu_role',
            'menu_id',
            'role_id'
        );
    }
}
