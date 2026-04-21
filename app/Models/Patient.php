<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'sexe',
        'telephone', 'adresse', 'email',
        'numero_dossier', 'type',
        'groupe_sanguin', 'allergies',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient) {
            $patient->numero_dossier = 'PAT-' . date('Y') . '-' . str_pad(
                Patient::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT
            );
        });
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function hospitalizations()
    {
        return $this->hasMany(Hospitalization::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function activeHospitalization()
    {
        return $this->hasOne(Hospitalization::class)->where('statut', 'en_cours');
    }
}
