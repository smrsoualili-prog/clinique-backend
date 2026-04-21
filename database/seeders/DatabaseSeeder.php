<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Services
        $cardiologie = Service::create(['nom' => 'Cardiologie', 'description' => 'Service cardiologie']);
        $pediatrie   = Service::create(['nom' => 'Pédiatrie',   'description' => 'Service pédiatrie']);
        $urgence     = Service::create(['nom' => 'Urgences',    'description' => 'Service urgences']);

        // Admin
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@clinique.dz',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Médecin
        User::create([
            'name'            => 'Dr. Ahmed Benali',
            'email'           => 'dr.benali@clinique.dz',
            'password'        => Hash::make('password'),
            'role'            => 'medecin',
            'service_id'      => $cardiologie->id,
            'specialite'      => 'Cardiologie',
            'is_chef_service' => true,
        ]);

        // Réception
        User::create([
            'name'     => 'Sara Meziane',
            'email'    => 'reception@clinique.dz',
            'password' => Hash::make('password'),
            'role'     => 'reception',
        ]);
    }
}
