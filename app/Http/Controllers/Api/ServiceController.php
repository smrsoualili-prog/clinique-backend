<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(Service::with('sousServices')->whereNull('parent_id')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:services,id',
            'is_active'   => 'boolean',
        ]);

        $service = Service::create($data);

        return response()->json($service, 201);
    }

    public function show(Service $service)
    {
        return response()->json($service->load('sousServices'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'nom'         => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:services,id',
            'is_active'   => 'boolean',
        ]);

        $service->update($data);

        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Service supprimé.']);
    }
}
