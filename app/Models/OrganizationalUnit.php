<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'man_power',
        'outlet_id',
        'is_active'
    ];

    public function childrenRecursive()
    {
        return $this->children()->with([
            'childrenRecursive',
            'employees.user',
            'employees.jabatan',
            'outlet'
        ]);
    }
    public function parent()
    {
        return $this->belongsTo(
            OrganizationalUnit::class,
            'parent_id'
        );
    }
    public function outlet()
    {
        return $this->belongsTo(
            Outlet::class,
            'outlet_id'
        );
    }
    public function children()
    {
        return $this->hasMany(
            OrganizationalUnit::class,
            'parent_id'
        );
    }

    public function employees()
    {
        return $this->hasMany(
            EmployeeProfile::class,
            'organizational_unit_id'
        );
    }

}
