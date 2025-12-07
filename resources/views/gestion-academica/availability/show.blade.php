    <!-- Sección de Disponibilidades -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📅 Disponibilidades Horarias</h5>
            <a href="{{ route('gestion-academica.teachers.availabilities.index', $teacher) }}" 
               class="btn btn-primary btn-sm">Gestionar Disponibilidades</a>
        </div>
        <div class="card-body">
            @php
                $days = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 
                        'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado'];
                $availabilities = $teacher->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();
            @endphp
            
            @if($availabilities->isEmpty())
                <p class="text-muted">No hay disponibilidades registradas.</p>
            @else
                <div class="row">
                    @foreach($days as $key => $day)
                        @php
                            $dayAvailabilities = $availabilities->where('day_of_week', $key);
                        @endphp
                        <div class="col-md-6 mb-3">
                            <strong>{{ $day }}:</strong>
                            @if($dayAvailabilities->isNotEmpty())
                                <div class="mt-1">
                                    @foreach($dayAvailabilities as $avail)
                                        <span class="badge {{ $avail->is_available ? 'bg-success' : 'bg-secondary' }} me-1 mb-1">
                                            {{ $avail->start_time->format('H:i') }}-{{ $avail->end_time->format('H:i') }}
                                            @if($avail->notes)
                                                <span title="{{ $avail->notes }}">ℹ️</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">Sin horarios registrados</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>