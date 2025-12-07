<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditController extends Controller
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
        return view('admin.audit.index');
    }

    public function show($id)
    {
        return view('admin.audit.show', compact('id'));
    }
}