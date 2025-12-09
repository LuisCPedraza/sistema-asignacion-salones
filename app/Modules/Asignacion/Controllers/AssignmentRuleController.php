<?php

namespace App\Modules\Asignacion\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Asignacion\Models\AssignmentRule;

class AssignmentRuleController extends Controller
{
    /**
     * HU10: Vista de configuración de reglas
     */
    public function reglas()
    {
        $rules = AssignmentRule::orderBy('weight', 'desc')->get();
        return view('asignacion.reglas', compact('rules'));
    }

    /**
     * Alias para index
     */
    public function index()
    {
        return $this->reglas();
    }

    /**
     * HU10: Método para actualizar (alias de actualizarReglas)
     */
    public function actualizar(Request $request)
    {
        return $this->actualizarReglas($request);
    }

    /**
     * HU10: Actualizar pesos de las reglas
     */
    public function actualizarReglas(Request $request)
    {
        $request->validate([
            'rules' => 'required|array',
            'rules.*.id' => 'required|exists:assignment_rules,id',
            'rules.*.weight' => 'required|numeric|min:0|max:100'
        ]);

        try {
            foreach ($request->rules as $ruleData) {
                // Convertir de porcentaje (0-100) a decimal (0-1)
                $weightDecimal = $ruleData['weight'] / 100;
                
                AssignmentRule::where('id', $ruleData['id'])
                    ->update(['weight' => $weightDecimal]);
            }

            return redirect()->route('asignacion.asignacion.reglas')
                ->with('success', '✅ Pesos de las reglas actualizados exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '❌ Error al actualizar los pesos: ' . $e->getMessage());
        }
    }

    /**
     * HU10: Activar/desactivar reglas (existente)
     */
    public function toggleRule(Request $request, AssignmentRule $rule)
    {
        $rule->update(['is_active' => !$rule->is_active]);

        $status = $rule->is_active ? 'activada' : 'desactivada';
        return redirect()->route('asignacion.reglas')
            ->with('success', "Regla {$rule->name} {$status}.");
    }
}