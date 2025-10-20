<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    // HU3
    public function store(StoreGroupRequest $request): JsonResponse
    {
        $group = Group::create($request->validated());
        return response()->json($group, 201);
    }

    // HU4 — LISTAR con búsqueda y filtro por activo
    public function index(Request $request): JsonResponse
    {
        $q      = $request->input('q');
        $activo = $request->input('activo'); // "0" | "1" | null

        $items = Group::query()
            ->when($q, fn($qry) => $qry->where('nombre','like',"%{$q}%"))
            ->when(!is_null($activo), fn($qry) => $qry->where('activo',(bool)$activo))
            ->orderBy('id','desc')
            ->paginate(10);

        return response()->json($items);
    }

    // HU4 — MOSTRAR detalle
    public function show(Group $group): JsonResponse
    {
        return response()->json($group);
    }

    // HU4 — EDITAR (reutilizamos validación de StoreGroupRequest)
    public function update(StoreGroupRequest $request, Group $group): JsonResponse
    {
        $group->update($request->validated());
        return response()->json($group);
    }

    // HU4 — DESACTIVAR (baja lógica)
    public function deactivate(Group $group): JsonResponse
    {
        $group->update(['activo' => false]);
        return response()->json(null, 204);
    }
}

