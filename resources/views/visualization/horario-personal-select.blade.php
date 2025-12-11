<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Profesor - Horario Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .teacher-card {
            transition: all 0.3s;
            cursor: pointer;
            border-left: 4px solid #48bb78;
        }
        .teacher-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">📅 Horario Personal de Profesores</h1>
                    <p class="mb-0 opacity-75">Selecciona un profesor para ver su horario</p>
                </div>
                <a href="{{ route('academic.dashboard') }}" class="btn btn-back">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text">🔍</span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar profesor por nombre o email...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4" id="teachersList">
            @forelse($teachers as $teacher)
                <div class="col-md-6 col-lg-4 teacher-item" 
                     data-name="{{ strtolower($teacher->first_name . ' ' . $teacher->last_name) }}"
                     data-email="{{ strtolower($teacher->user->email ?? '') }}">
                    <div class="card teacher-card h-100 shadow-sm" 
                         onclick="window.location.href='{{ route('visualizacion.horario.personal', ['teacher_id' => $teacher->id]) }}'">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-2">
                                        👨‍🏫 {{ $teacher->first_name }} {{ $teacher->last_name }}
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        📧 {{ $teacher->user->email ?? 'Sin email' }}
                                    </p>
                                    @if($teacher->specialization)
                                        <p class="text-muted small mb-0">
                                            🎓 {{ $teacher->specialization }}
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <span class="badge bg-success">Activo</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                📊 Click para ver horario
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No hay profesores activos registrados.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.teacher-item');
            
            items.forEach(item => {
                const name = item.dataset.name;
                const email = item.dataset.email;
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
