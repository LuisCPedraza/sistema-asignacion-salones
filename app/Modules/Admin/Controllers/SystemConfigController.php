<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado. Se requiere rol de administrador.');
            }
            return $next($request);
        });
    }

    /**
     * Mostrar listado de configuraciones
     */
    public function index()
    {
        $configs = SystemConfig::all();
        
        return view('admin.config.index', [
            'configs' => $configs,
            'groupedConfigs' => [
                'institution' => SystemConfig::where('key', 'like', 'institution.%')->get(),
                'schedule' => SystemConfig::where('key', 'like', 'schedule.%')->get(),
                'algorithm' => SystemConfig::where('key', 'like', 'algorithm.%')->get(),
                'audit' => SystemConfig::where('key', 'like', 'audit.%')->get(),
            ]
        ]);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $configs = SystemConfig::all();
        
        return view('admin.config.edit', [
            'configs' => $configs,
            'institution' => [
                'name' => SystemConfig::get('institution.name', 'Universidad Ejemplo'),
                'code' => SystemConfig::get('institution.code', 'UNIV-001'),
            ],
            'schedule' => [
                'work_start_time' => SystemConfig::get('schedule.work_start_time', '08:00:00'),
                'work_end_time' => SystemConfig::get('schedule.work_end_time', '17:00:00'),
                'lunch_start_time' => SystemConfig::get('schedule.lunch_start_time', '12:00:00'),
                'lunch_end_time' => SystemConfig::get('schedule.lunch_end_time', '13:00:00'),
            ],
            'algorithm' => [
                'min_score_threshold' => SystemConfig::get('algorithm.min_score_threshold', '0.6'),
                'max_attempts' => SystemConfig::get('algorithm.max_attempts', '15'),
            ],
            'audit' => [
                'enabled' => SystemConfig::get('audit.enabled', '1'),
                'retention_days' => SystemConfig::get('audit.retention_days', '90'),
            ]
        ]);
    }

    /**
     * Actualizar configuraciones
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Institución
            'institution_name' => 'required|string|max:255',
            'institution_code' => 'required|string|max:50',
            
            // Horarios
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
            'lunch_start_time' => 'required|date_format:H:i',
            'lunch_end_time' => 'required|date_format:H:i|after:lunch_start_time',
            
            // Algoritmo
            'min_score_threshold' => 'required|numeric|min:0|max:1',
            'max_attempts' => 'required|integer|min:1|max:100',
            
            // Auditoría
            'audit_enabled' => 'boolean',
            'audit_retention_days' => 'required|integer|min:1|max:365',
        ]);

        try {
            // Actualizar configuraciones por sección
            
            // Institución
            SystemConfig::set('institution.name', $validated['institution_name'], 'string', 'Nombre de la institución educativa');
            SystemConfig::set('institution.code', $validated['institution_code'], 'string', 'Código único de la institución');
            
            // Horarios
            SystemConfig::set('schedule.work_start_time', $validated['work_start_time'] . ':00', 'string', 'Hora de inicio de jornada laboral');
            SystemConfig::set('schedule.work_end_time', $validated['work_end_time'] . ':00', 'string', 'Hora de fin de jornada laboral');
            SystemConfig::set('schedule.lunch_start_time', $validated['lunch_start_time'] . ':00', 'string', 'Hora de inicio de almuerzo');
            SystemConfig::set('schedule.lunch_end_time', $validated['lunch_end_time'] . ':00', 'string', 'Hora de fin de almuerzo');
            
            // Algoritmo
            SystemConfig::set('algorithm.min_score_threshold', $validated['min_score_threshold'], 'string', 'Puntuación mínima aceptable para asignaciones');
            SystemConfig::set('algorithm.max_attempts', $validated['max_attempts'], 'integer', 'Máximo número de intentos en el algoritmo');
            
            // Auditoría
            SystemConfig::set('audit.enabled', $request->has('audit_enabled') ? '1' : '0', 'boolean', 'Habilitar registro de auditoría');
            SystemConfig::set('audit.retention_days', $validated['audit_retention_days'], 'integer', 'Días de retención de logs de auditoría');

            return redirect()->route('admin.config.index')
                ->with('success', '✅ Configuración actualizada exitosamente (HU19).');
        } catch (\Exception $e) {
            return redirect()->route('admin.config.edit')
                ->with('error', '❌ Error al actualizar configuración: ' . $e->getMessage());
        }
    }
}