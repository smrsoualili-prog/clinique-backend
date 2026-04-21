<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['numero', 'service_id', 'capacite', 'type', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function litsDispo()
    {
        return $this->beds()->where('statut', 'libre');
    }
}