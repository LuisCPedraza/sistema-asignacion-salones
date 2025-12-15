<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asignación de Salones - @yield('title', 'Inicio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
        }
        .main-content {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            .sidebar {
                width: 250px;
            }
            .main-content {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-university me-2"></i>Sistema de Salones
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-item-text small text-muted">Rol: {{ auth()->user()->role->name ?? 'Sin rol' }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('secretaria_administrativa'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->hasRole('coordinador') || auth()->user()->hasRole('secretaria_coordinacion'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('academic.dashboard') }}">
                                <i class="fas fa-graduation-cap me-2"></i>Dashboard Académico
                            </a>
                        </li>
                        
                        <!-- Gestión Académica -->
                        <li class="nav-item">
                            <a class="nav-link" href="#gestionAcademica" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                <i class="fas fa-cogs me-2"></i>Gestión Académica
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                            </a>
                            <div class="collapse" id="gestionAcademica">
                                <ul class="nav flex-column ms-3 mt-2">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('careers.index') }}">
                                            <i class="fas fa-book me-2"></i>Carreras
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('semesters.index') }}">
                                            <i class="fas fa-layer-group me-2"></i>Semestres
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('subjects.index') }}">
                                            <i class="fas fa-book-open me-2"></i>Materias
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gestion-academica.student-groups.index') }}">
                                <i class="fas fa-users me-2"></i>Grupos Estudiantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('visualizacion.horario.semestral') }}">
                                <i class="fas fa-calendar-alt me-2"></i>Horario Semestral
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('visualizacion.horario.malla-semestral') }}">
                                <i class="fas fa-table me-2"></i>Malla Horaria
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->hasRole('coordinador_infraestructura') || auth()->user()->hasRole('secretaria_infraestructura'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('infraestructura.dashboard') }}">
                                <i class="fas fa-building me-2"></i>Dashboard Infraestructura
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->hasRole('profesor') || auth()->user()->hasRole('profesor_invitado'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profesor.dashboard') }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Mi Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('asignacion.teacher.schedule') }}">
                                <i class="fas fa-calendar-check me-2"></i>Mi Horario
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </nav>
            @endauth

            <!-- Page Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>Por favor corrige los siguientes errores:
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="mt-3">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>