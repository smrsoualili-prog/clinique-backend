<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'medecin', 'infirmier', 'reception'])->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->foreignId('service_id')->nullable()->after('is_active');
            $table->string('phone')->nullable()->after('service_id');
            $table->string('specialite')->nullable()->after('phone');
            $table->boolean('is_chef_service')->default(false)->after('specialite');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'service_id', 'phone', 'specialite', 'is_chef_service']);
        });
    }
};
