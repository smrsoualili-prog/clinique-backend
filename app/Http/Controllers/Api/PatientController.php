<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_dossier', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'            => 'required|string|max:100',
            'prenom'         => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'sexe'           => 'required|in:M,F',
            'telephone'      => 'nullable|string|max:20',
            'adresse'        => 'nullable|string',
            'email'          => 'nullable|email',
            'groupe_sanguin' => 'nullable|string',
            'allergies'      => 'nullable|string',
            'type'           => 'required|in:consultation,hospitalise',
        ]);

        $patient = Patient::create($data);

        return response()->json($patient, 201);
    }

    public function show(Patient $patient)
    {
        return response()->json(
            $patient->load([
                'appointments.medecin',
                'appointments.service',
                'activeHospitalization.bed.room',
            ])
        );
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'nom'            => 'sometimes|string|max:100',
            'prenom'         => 'sometimes|string|max:100',
            'date_naissance' => 'sometimes|date',
            'sexe'           => 'sometimes|in:M,F',
            'telephone'      => 'nullable|string|max:20',
            'adresse'        => 'nullable|string',
            'email'          => 'nullable|email',
            'groupe_sanguin' => 'nullable|string',
            'allergies'      => 'nullable|string',
            'type'           => 'sometimes|in:consultation,hospitalise',
        ]);

        $patient->update($data);

        return response()->json($patient);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(['message' => 'Patient supprimé.']);
    }

    public function historique(Patient $patient)
    {
        return response()->json([
            'patient'         => $patient,
            'medical_records' => $patient->medicalRecords()
                                         ->with(['medecin', 'prescriptions'])
                                         ->orderBy('created_at', 'desc')
                                         ->get(),
            'appointments'    => $patient->appointments()
                                         ->with(['medecin', 'service'])
                                         ->orderBy('date_heure', 'desc')
                                         ->get(),
        ]);
    }
}