<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospitalization extends Model
{
    protected $fillable = [
        'patient_id', 'bed_id', 'medecin_id', 'service_id',
        'date_admission', 'date_sortie', 'statut', 'motif'
    ];

    protected $casts = [
        'date_admission' => 'datetime',
        'date_sortie'    => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
