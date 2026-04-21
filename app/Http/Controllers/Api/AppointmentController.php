<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'medecin', 'service']);

        if ($request->has('medecin_id')) {
            $query->where('medecin_id', $request->medecin_id);
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('date')) {
            $query->whereDate('date_heure', $request->date);
        }

        return response()->json(
            $query->orderBy('date_heure', 'asc')->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'medecin_id'     => 'required|exists:users,id',
            'service_id'     => 'required|exists:services,id',
            'date_heure'     => 'required|date',
            'duree_minutes'  => 'integer|min:15',
            'notes'          => 'nullable|string',
        ]);

        $appointment = Appointment::create($data);

        return response()->json(
            $appointment->load(['patient', 'medecin', 'service']), 201
        );
    }

    public function show(Appointment $appointment)
    {
        return response()->json(
            $appointment->load(['patient', 'medecin', 'service', 'medicalRecord'])
        );
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'date_heure'    => 'sometimes|date',
            'duree_minutes' => 'sometimes|integer|min:15',
            'statut'        => 'sometimes|in:planifie,confirme,annule,termine',
            'notes'         => 'nullable|string',
        ]);

        $appointment->update($data);

        return response()->json($appointment->load(['patient', 'medecin', 'service']));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['message' => 'RDV supprimé.']);
    }
}
