@extends('layouts.app')

@section('content')
<style>
    h1 { font-size: 32px; }
    h3, h6 { font-size: 20px; }
    .form-control, .form-select, .btn, table, th, td { font-size: 20px; }
    .card-title, .card-body, strong { font-size: 20px; }
    .badge { font-size: 18px; }
    .text-muted, small { font-size: 18px; }
</style>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>👥 Profesores Invitados (HU8/HU14)</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Crear Profesor Invitado</a>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total</h6>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success bg-opacity-10">
                <div class="card-body">
                    <h6 class="card-title text-muted">✅ Activos</h6>
                    <h3 class="mb-0">{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning bg-opacity-10">
                <div class="card-body">
                    <h6 class="card-title text-muted">⏰ Por expirar</h6>
                    <h3 class="mb-0">{{ $stats['expiring_soon'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger bg-opacity-10">
                <div class="card-body">
                    <h6 class="card-title text-muted">❌ Expirados</h6>
                    <h3 class="mb-0">{{ $stats['expired'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✅ Activos</option>
                        <option value="expiring_soon" {{ request('status') === 'expiring_soon' ? 'selected' : '' }}>⏰ Por expirar en 7 días</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>❌ Expirados</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">🔍 Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de profesores invitados -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Profesor</th>
                        <th>Email</th>
                        <th>Acceso Expira</th>
                        <th>Días Restantes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guestTeachers as $teacher)
                        @php
                            $isValid = $teacher->isAccessValid();
                            $expiresAt = $teacher->access_expires_at;
                            $now = now();
                            
                            // Calcular días restantes correctamente
                            if ($expiresAt) {
                                if ($expiresAt->isFuture()) {
                                    // Usar floor para obtener días completos sin decimales
                                    $daysRemaining = floor($now->diffInDays($expiresAt, false));
                                    $hoursRemaining = floor($now->copy()->addDays($daysRemaining)->diffInHours($expiresAt, false));
                                    
                                    // Formatear texto legible
                                    if ($daysRemaining > 0) {
                                        $remainingText = "{$daysRemaining} día" . ($daysRemaining > 1 ? 's' : '');
                                        if ($hoursRemaining > 0) {
                                            $remainingText .= " y {$hoursRemaining} hora" . ($hoursRemaining > 1 ? 's' : '');
                                        }
                                    } else {
                                        $remainingText = "{$hoursRemaining} hora" . ($hoursRemaining > 1 ? 's' : '');
                                    }
                                } else {
                                    $daysRemaining = -1;
                                    $remainingText = 'Expirado';
                                }
                            } else {
                                $daysRemaining = null;
                                $remainingText = null;
                            }
                            
                            $statusClass = !$isValid ? 'danger' : ($daysRemaining !== null && $daysRemaining <= 7 ? 'warning' : 'success');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $teacher->full_name }}</strong>
                                @if($teacher->user)
                                    <br>
                                    <small class="text-muted">{{ $teacher->user->name }}</small>
                                @endif
                            </td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                @if($teacher->access_expires_at)
                                    <strong>{{ $teacher->access_expires_at->format('d/m/Y H:i') }}</strong>
                                @else
                                    <span class="badge bg-secondary">Sin expiración</span>
                                @endif
                            </td>
                            <td>
                                @if($remainingText)
                                    @if($daysRemaining >= 0)
                                        <strong class="text-{{ $statusClass }}">{{ $remainingText }}</strong>
                                    @else
                                        <strong class="text-danger">{{ $remainingText }}</strong>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!$isValid)
                                    <span class="badge bg-danger">❌ Expirado</span>
                                @elseif($daysRemaining !== null && $daysRemaining <= 7)
                                    <span class="badge bg-warning">⏰ Por expirar</span>
                                @else
                                    <span class="badge bg-success">✅ Activo</span>
                                @endif
                            </td>
                            <td>
                                @if($teacher->user)
                                    <a href="{{ route('admin.users.edit', $teacher->user) }}" 
                                       class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                                    <a href="{{ route('admin.guest-teachers.show', $teacher) }}" 
                                       class="btn btn-sm btn-outline-info">👁️ Ver</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay profesores invitados registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if($guestTeachers->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $guestTeachers->links() }}
        </div>
    @endif
</div>
@endsection
