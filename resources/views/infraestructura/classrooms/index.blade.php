@extends('layouts.app')

@section('content')
<style>
    .classroom-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .classroom-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }
    
    .classroom-header .subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
        font-size: 0.95rem;
    }
    
    .stats-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .stat-box {
        flex: 1;
        min-width: 200px;
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }
    
    .stat-box.total { border-left-color: #667eea; }
    .stat-box.active { border-left-color: #48bb78; }
    .stat-box.inactive { border-left-color: #f56565; }
    .stat-box.capacity { border-left-color: #ed8936; }
    
    .stat-box .stat-label {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .stat-box .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .classroom-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .classroom-card .card-header-custom {
        background: #f7fafc;
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .classroom-card .card-header-custom h5 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #2d3748;
    }
    
    .classroom-table {
        margin-bottom: 0;
    }
    
    .classroom-table thead th {
        background: #edf2f7;
        color: #4a5568;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem 0.75rem;
    }
    
    .classroom-table tbody tr {
        transition: background-color 0.2s;
    }
    
    .classroom-table tbody tr:hover {
        background-color: #f7fafc;
    }
    
    .classroom-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        color: #4a5568;
        border-top: 1px solid #e2e8f0;
    }
    
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .type-aula { background: #bee3f8; color: #2c5282; }
    .type-laboratorio { background: #c6f6d5; color: #22543d; }
    .type-auditorio { background: #fbb6ce; color: #702459; }
    .type-sala { background: #fbd38d; color: #7c2d12; }
    .type-taller { background: #d6bcfa; color: #44337a; }
    
    .action-btn {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        border: none;
        cursor: pointer;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .btn-view { background: #4299e1; color: white; }
    .btn-edit { background: #ed8936; color: white; }
    .btn-schedule { background: #48bb78; color: white; }
    .btn-deactivate { background: #f56565; color: white; }
    
    /* Paginación personalizada con números */
    .pagination {
        margin: 1.5rem 0 0 0;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        margin: 0;
    }
    
    .pagination .page-link {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        transition: all 0.2s;
        font-weight: 500;
    }
    
    .pagination .page-item.active .page-link {
        background: #667eea;
        border-color: #667eea;
        color: white;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.4);
    }
    
    .pagination .page-link:hover {
        background: #edf2f7;
        border-color: #cbd5e0;
        color: #2d3748;
    }
    
    .pagination .page-item.disabled .page-link {
        background: #f7fafc;
        border-color: #e2e8f0;
        color: #cbd5e0;
    }
    
    /* Nota: No ocultamos primer/último item para no ocultar páginas 1 y última */

    /* Ocultar enlaces prev/next generados por Laravel */
    .pagination .page-link[rel="prev"],
    .pagination .page-link[rel="next"],
    .pagination .page-link[aria-label="pagination.previous"],
    .pagination .page-link[aria-label="pagination.next"] {
        display: none !important;
    }
    
    .btn-create-new {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }
    
    .btn-create-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #718096;
    }
    
    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Chips de filtros activos */
    .filter-chips {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin: 0 0 1rem 0;
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #edf2f7;
        color: #2d3748;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        font-size: 0.85rem;
    }
    .filter-chip .remove {
        color: #4a5568;
        text-decoration: none;
        font-weight: 600;
    }
    .filter-chip .remove:hover {
        color: #e53e3e;
    }
</style>

<div class="container mt-4">
    <!-- Header -->
    <div class="classroom-header">
        <h1>🏢 Gestión de Salones</h1>
        <p class="subtitle">Administra los espacios físicos del campus</p>
    </div>

    <!-- Mensajes de éxito -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✓</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="stats-row">
        <div class="stat-box total">
            <div class="stat-label">Total de Salones</div>
            <div class="stat-value">{{ $classrooms->total() }}</div>
        </div>
        <div class="stat-box active">
            <div class="stat-label">Salones Activos</div>
            <div class="stat-value">{{ $classrooms->where('is_active', true)->count() }}</div>
        </div>
        <div class="stat-box inactive">
            <div class="stat-label">Salones Inactivos</div>
            <div class="stat-value">{{ $classrooms->where('is_active', false)->count() }}</div>
        </div>
        <div class="stat-box capacity">
            <div class="stat-label">Capacidad Total</div>
            <div class="stat-value">{{ $classrooms->sum('capacity') }}</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="classroom-card" style="margin-bottom: 1rem;">
        <div class="card-header-custom">
            <h5>Filtros</h5>
            <a href="{{ route('infraestructura.classrooms.index') }}" class="action-btn btn-view" title="Limpiar filtros">↺ Limpiar</a>
        </div>
        <div style="padding: 1rem 1.5rem;">
            <form method="GET" action="{{ route('infraestructura.classrooms.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Edificio</label>
                        <select name="building_id" class="form-select">
                            <option value="">Todos</option>
                            @isset($buildings)
                                @foreach($buildings as $b)
                                    <option value="{{ $b->id }}" {{ request('building_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select">
                            @php $t = request('type'); @endphp
                            <option value="">Todos</option>
                            <option value="aula" {{ $t==='aula' ? 'selected' : '' }}>Aula</option>
                            <option value="laboratorio" {{ $t==='laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                            <option value="auditorio" {{ $t==='auditorio' ? 'selected' : '' }}>Auditorio</option>
                            <option value="sala_reuniones" {{ $t==='sala_reuniones' ? 'selected' : '' }}>Sala de Reuniones</option>
                            <option value="taller" {{ $t==='taller' ? 'selected' : '' }}>Taller</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="is_active" class="form-select">
                            @php $a = request('is_active'); @endphp
                            <option value="">Todos</option>
                            <option value="1" {{ $a==='1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $a==='0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Buscar</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Código o nombre">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Capacidad mínima</label>
                        <input type="number" min="0" class="form-control" name="capacity_min" value="{{ request('capacity_min') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Capacidad máxima</label>
                        <input type="number" min="0" class="form-control" name="capacity_max" value="{{ request('capacity_max') }}">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn-create-new">🔎 Aplicar filtros</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Salones -->
    <div class="classroom-card">
        <div class="card-header-custom">
            <h5>Listado de Salones</h5>
            <a href="{{ route('infraestructura.classrooms.create') }}" class="btn-create-new">
                ➕ Crear Nuevo Salón
            </a>
        </div>
        
        <!-- Chips de filtros activos -->
        @php
            $params = request()->all();
            $chipUrl = function(array $remove) use ($params) {
                $q = array_diff_key($params, array_flip($remove));
                $base = url()->current();
                return count($q) ? ($base . '?' . http_build_query($q)) : $base;
            };
        @endphp
        <div class="filter-chips" style="padding: 0.75rem 1.5rem 0;">
            @if(request('building_id'))
                <span class="filter-chip">🏢 Edificio: 
                    <strong>{{ optional(($buildings ?? collect())->firstWhere('id', (int)request('building_id')))->name ?? request('building_id') }}</strong>
                    <a class="remove" href="{{ $chipUrl(['building_id']) }}" title="Quitar">×</a>
                </span>
            @endif
            @if(request('type'))
                <span class="filter-chip">🏷️ Tipo: 
                    <strong>{{ request('type') }}</strong>
                    <a class="remove" href="{{ $chipUrl(['type']) }}" title="Quitar">×</a>
                </span>
            @endif
            @if(request('is_active') !== null && request('is_active') !== '')
                <span class="filter-chip">⚙ Estado: 
                    <strong>{{ request('is_active')==='1' ? 'Activo' : 'Inactivo' }}</strong>
                    <a class="remove" href="{{ $chipUrl(['is_active']) }}" title="Quitar">×</a>
                </span>
            @endif
            @if(request('capacity_min'))
                <span class="filter-chip">👥 Capacidad ≥ 
                    <strong>{{ request('capacity_min') }}</strong>
                    <a class="remove" href="{{ $chipUrl(['capacity_min']) }}" title="Quitar">×</a>
                </span>
            @endif
            @if(request('capacity_max'))
                <span class="filter-chip">👥 Capacidad ≤ 
                    <strong>{{ request('capacity_max') }}</strong>
                    <a class="remove" href="{{ $chipUrl(['capacity_max']) }}" title="Quitar">×</a>
                </span>
            @endif
            @if(request('search'))
                <span class="filter-chip">🔎 Búsqueda: 
                    <strong>{{ request('search') }}</strong>
                    <a class="remove" href="{{ $chipUrl(['search']) }}" title="Quitar">×</a>
                </span>
            @endif
        </div>
        
        <div class="table-responsive">
            <table class="table classroom-table">
                <thead>
                    <tr>
                        <th>CÓDIGO</th>
                        <th>NOMBRE</th>
                        <th>EDIFICIO</th>
                        <th>CAPACIDAD</th>
                        <th>TIPO</th>
                        <th>PISO</th>
                        <th>ESTADO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $classroom)
                        <tr>
                            <td><strong>{{ $classroom->code }}</strong></td>
                            <td>{{ $classroom->name }}</td>
                            <td>
                                @if($classroom->building)
                                    🏢 {{ $classroom->building->name }}
                                @else
                                    <span style="color: #a0aec0;">Sin edificio</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #2d3748;">{{ $classroom->capacity }}</span>
                                <span style="color: #a0aec0; font-size: 0.85rem;">personas</span>
                            </td>
                            <td>
                                @php
                                    $typeConfig = [
                                        'aula' => ['label' => 'Aula', 'icon' => '📚', 'class' => 'type-aula'],
                                        'laboratorio' => ['label' => 'Laboratorio', 'icon' => '🔬', 'class' => 'type-laboratorio'],
                                        'auditorio' => ['label' => 'Auditorio', 'icon' => '🎭', 'class' => 'type-auditorio'],
                                        'sala_reuniones' => ['label' => 'Sala de Reuniones', 'icon' => '💼', 'class' => 'type-sala'],
                                        'taller' => ['label' => 'Taller', 'icon' => '🔧', 'class' => 'type-taller']
                                    ];
                                    $config = $typeConfig[$classroom->type] ?? ['label' => $classroom->type, 'icon' => '📍', 'class' => 'type-aula'];
                                @endphp
                                <span class="type-badge {{ $config['class'] }}">
                                    {{ $config['icon'] }} {{ $config['label'] }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 500;">Piso {{ $classroom->floor }}</span>
                                @if($classroom->wing)
                                    <br><small style="color: #a0aec0;">Ala {{ $classroom->wing }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $classroom->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $classroom->is_active ? '✓ Activo' : '✗ Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                    <a href="{{ route('infraestructura.classrooms.show', $classroom) }}" 
                                       class="action-btn btn-view" title="Ver detalles">
                                        👁️ Ver
                                    </a>
                                    <a href="{{ route('infraestructura.classrooms.edit', $classroom) }}" 
                                       class="action-btn btn-edit" title="Editar">
                                        ✏️ Editar
                                    </a>
                                    <a href="{{ route('infraestructura.classrooms.availabilities.index', $classroom) }}" 
                                       class="action-btn btn-schedule" title="Disponibilidad">
                                        📅
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('infraestructura.classrooms.destroy', $classroom) }}" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de desactivar este salón?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn btn-deactivate" title="Desactivar">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div>🏢</div>
                                    <h5>No hay salones registrados</h5>
                                    <p>Comienza creando tu primer salón usando el botón "Crear Nuevo Salón"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación con números -->
        <div style="padding: 1rem 1.5rem; background: #f7fafc; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="color: #718096; font-size: 0.9rem;">
                    Mostrando {{ $classrooms->firstItem() ?? 0 }} - {{ $classrooms->lastItem() ?? 0 }} 
                    de {{ $classrooms->total() }} salones
                </div>
                <div>
                    <nav aria-label="Paginación de salones">
                        <ul class="pagination">
                            @php
                                $totalPages = $classrooms->lastPage();
                                $current = $classrooms->currentPage();
                                $range = 2; // Número de páginas a mostrar a cada lado de la actual
                                $start = max(1, $current - $range);
                                $end = min($totalPages, $current + $range);
                            @endphp
                            
                            {{-- Primera página --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $classrooms->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif
                            
                            {{-- Rango de páginas --}}
                            @for ($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $i === $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $classrooms->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            {{-- Última página --}}
                            @if($end < $totalPages)
                                @if($end < $totalPages - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $classrooms->url($totalPages) }}">{{ $totalPages }}</a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection