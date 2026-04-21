<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id', 'numero',
        'montant_total', 'statut',
        'date_emission', 'date_paiement', 'details'
    ];

    protected $casts = [
        'date_emission'  => 'datetime',
        'date_paiement'  => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
