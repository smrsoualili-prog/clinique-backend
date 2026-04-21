<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'is_active', 'service_id', 'phone',
        'specialite', 'is_chef_service'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active'      => 'boolean',
        'is_chef_service' => 'boolean',
    ];

    public function isAdmin()     { return $this->role === 'admin'; }
    public function isMedecin()   { return $this->role === 'medecin'; }
    public function isInfirmier() { return $this->role === 'infirmier'; }
    public function isReception() { return $this->role === 'reception'; }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'medecin_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'medecin_id');
    }
}