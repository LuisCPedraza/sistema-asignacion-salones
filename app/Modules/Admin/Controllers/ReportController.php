<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
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
        return view('admin.reports.index');
    }

    public function utilization()
    {
        return view('admin.reports.utilization');
    }

    public function statistics()
    {
        return view('admin.reports.statistics');
    }
}