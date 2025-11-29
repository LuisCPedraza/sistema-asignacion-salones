<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
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

    public function index()
    {
        return view('admin.config.index');
    }

    public function edit()
    {
        return view('admin.config.edit');
    }

    public function update(Request $request)
    {
        // Lógica de actualización de configuración
        return redirect()->route('admin.config.index')
            ->with('success', 'Configuración actualizada exitosamente (HU19).');
    }
}