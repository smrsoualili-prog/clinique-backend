<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::with('medicalRecord.patient');

        if ($request->has('medical_record_id')) {
            $query->where('medical_record_id', $request->medical_record_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'medicament'        => 'required|string',
            'dosage'            => 'required|string',
            'frequence'         => 'required|string',
            'duree_jours'       => 'required|integer|min:1',
            'instructions'      => 'nullable|string',
        ]);

        $prescription = Prescription::create($data);

        return response()->json($prescription, 201);
    }

    public function show(Prescription $prescription)
    {
        return response()->json($prescription->load('medicalRecord.patient'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $data = $request->validate([
            'medicament'   => 'sometimes|string',
            'dosage'       => 'sometimes|string',
            'frequence'    => 'sometimes|string',
            'duree_jours'  => 'sometimes|integer|min:1',
            'instructions' => 'nullable|string',
        ]);

        $prescription->update($data);

        return response()->json($prescription);
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return response()->json(['message' => 'Prescription supprimée.']);
    }
}
