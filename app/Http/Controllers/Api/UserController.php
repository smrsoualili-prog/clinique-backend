<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('service');

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:6',
            'role'            => 'required|in:admin,medecin,infirmier,reception',
            'service_id'      => 'nullable|exists:services,id',
            'phone'           => 'nullable|string',
            'specialite'      => 'nullable|string',
            'is_chef_service' => 'boolean',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user->load('service'), 201);
    }

    public function show(User $user)
    {
        return response()->json($user->load('service'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'            => 'sometimes|string|max:100',
            'email'           => 'sometimes|email|unique:users,email,' . $user->id,
            'password'        => 'sometimes|min:6',
            'role'            => 'sometimes|in:admin,medecin,infirmier,reception',
            'service_id'      => 'nullable|exists:services,id',
            'phone'           => 'nullable|string',
            'specialite'      => 'nullable|string',
            'is_chef_service' => 'boolean',
            'is_active'       => 'boolean',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user->load('service'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé.']);
    }
}