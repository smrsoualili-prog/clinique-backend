<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['nom', 'description', 'parent_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function sousServices()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
