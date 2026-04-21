<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = ['numero', 'room_id', 'statut'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function activeHospitalization()
    {
        return $this->hasOne(Hospitalization::class)->where('statut', 'en_cours');
    }
}