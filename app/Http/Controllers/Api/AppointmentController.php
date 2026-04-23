<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('patient');

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'montant_total' => 'required|numeric|min:0',
            'details'       => 'nullable|string',
        ]);

        $data['date_emission'] = now();
        $data['numero'] = 'FAC-' . date('Y') . '-' . str_pad(
            Invoice::count() + 1, 4, '0', STR_PAD_LEFT
        );

        $invoice = Invoice::create($data);

        return response()->json($invoice->load('patient'), 201);
    }

    public function show(Invoice $invoice)
    {
        return response()->json($invoice->load('patient'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'montant_total' => 'sometimes|numeric|min:0',
            'statut'        => 'sometimes|in:en_attente,payee,annulee',
            'details'       => 'nullable|string',
        ]);

        if (isset($data['statut']) && $data['statut'] === 'payee') {
            $data['date_paiement'] = now();
        }

        $invoice->update($data);

        return response()->json($invoice->load('patient'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['message' => 'Facture supprimée.']);
    }
}