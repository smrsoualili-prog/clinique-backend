<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient', 'medecin', 'prescriptions']);

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('medecin_id')) {
            $query->where('medecin_id', $request->medecin_id);
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'          => 'required|exists:patients,id',
            'medecin_id'          => 'required|exists:users,id',
            'appointment_id'      => 'nullable|exists:appointments,id',
            'poids'               => 'nullable|numeric',
            'taille'              => 'nullable|numeric',
            'tension_arterielle'  => 'nullable|string',
            'frequence_cardiaque' => 'nullable|integer',
            'temperature'         => 'nullable|numeric',
            'symptomes'           => 'nullable|string',
            'diagnostic'          => 'nullable|string',
            'traitement'          => 'nullable|string',
            'notes'               => 'nullable|string',
        ]);

        $record = MedicalRecord::create($data);

        return response()->json(
            $record->load(['patient', 'medecin', 'prescriptions']), 201
        );
    }

    public function show(MedicalRecord $medicalRecord)
    {
        return response()->json(
            $medicalRecord->load(['patient', 'medecin', 'prescriptions'])
        );
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $data = $request->validate([
            'poids'               => 'nullable|numeric',
            'taille'              => 'nullable|numeric',
            'tension_arterielle'  => 'nullable|string',
            'frequence_cardiaque' => 'nullable|integer',
            'temperature'         => 'nullable|numeric',
            'symptomes'           => 'nullable|string',
            'diagnostic'          => 'nullable|string',
            'traitement'          => 'nullable|string',
            'notes'               => 'nullable|string',
        ]);

        $medicalRecord->update($data);

        return response()->json(
            $medicalRecord->load(['patient', 'medecin', 'prescriptions'])
        );
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return response()->json(['message' => 'Dossier médical supprimé.']);
    }
}
