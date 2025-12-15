@extends('layouts.app')

@section('content')
<style>
    h1 { font-size: 32px; }
    h5 { font-size: 20px; }
    p, strong { font-size: 20px; }
    .card-body { font-size: 20px; }
    .btn { font-size: 20px; }
</style>
<div class="container mt-4">
    <h1>👤 Detalle de Usuario: {{ $user->name }}</h1>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Información Personal</h5>
                    <p><strong>Nombre:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Rol:</strong> 
                        <span class="badge bg-info">{{ $user->role->name ?? 'Sin rol' }}</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Estado del Usuario</h5>
                    <p><strong>Estado:</strong> 
                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                    <p><strong>Registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Última actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    @if($user->email_verified_at)
                        <p><strong>Email verificado:</strong> {{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                    @else
                        <p><strong>Email verificado:</strong> <span class="badge bg-warning">No verificado</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">✏️ Editar</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Volver</a>
        
        @if($user->is_active && $user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('¿Desactivar usuario {{ $user->name }}?')">
                    🚫 Desactivar
                </button>
            </form>
        @endif
    </div>
</div>
@endsection