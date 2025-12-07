<?php

namespace App\Modules\Asignacion\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;

// Modelos correctos del sistema modular
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\AssignmentRule;
use App\Modules\Asignacion\Models\Assignment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    public function index()
    {
        return view('asignacion.index');
    }

    public function automatica()
    {
        $datos = [
            'gruposCount' => StudentGroup::where('is_active', true)->count(),
            'salonesCount' => Classroom::where('is_active', true)->count(),
            'profesoresCount' => Teacher::where('is_active', true)->count(),
            'franjasCount' => TimeSlot::count(),
            'reglasActivas' => AssignmentRule::where('is_active', true)->orderBy('weight', 'desc')->get(),
            'asignacionesExistentes' => Assignment::count(),
        ];

        return view('asignacion.automatica', $datos);
    }

    public function ejecutarAutomatica(Request $request)
    {
        $inicio = microtime(true);

        try {
            DB::beginTransaction();

            $algorithm = new AssignmentAlgorithm();
            $asignaciones = $algorithm->generateAssignments();

            DB::commit();

            $duracion = round(microtime(true) - $inicio, 2);

            return redirect()
                ->route('asignacion.asignacion.automatica')
                ->with('success_message', "¡Asignación completada con éxito! Se asignaron " . count($asignaciones) . " grupos en {$duracion} segundos");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en asignación automática', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('asignacion.asignacion.automatica')
                ->with('error_message', 'Ocurrió un error durante la asignación: ' . $e->getMessage());
        }
    }

    public function manual()
    {
        return view('asignacion.manual');
    }

    public function conflictos()
    {
        return view('asignacion.conflictos');
    }
}