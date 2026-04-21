<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Bed;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['service', 'beds']);

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero'     => 'required|string',
            'service_id' => 'required|exists:services,id',
            'capacite'   => 'required|integer|min:1',
            'type'       => 'required|in:standard,vip,urgence',
        ]);

        $room = Room::create($data);

        // Créer les lits automatiquement
        for ($i = 1; $i <= $data['capacite']; $i++) {
            Bed::create([
                'numero'  => $data['numero'] . '-' . $i,
                'room_id' => $room->id,
                'statut'  => 'libre',
            ]);
        }

        return response()->json($room->load('beds'), 201);
    }

    public function show(Room $room)
    {
        return response()->json($room->load(['service', 'beds']));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'numero'     => 'sometimes|string',
            'service_id' => 'sometimes|exists:services,id',
            'type'       => 'sometimes|in:standard,vip,urgence',
            'is_active'  => 'sometimes|boolean',
        ]);

        $room->update($data);

        return response()->json($room->load(['service', 'beds']));
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Chambre supprimée.']);
    }
}