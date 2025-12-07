<?php

namespace App\Http\Controllers\AcademicManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\AcademicManagement\Models\StudentGroup;
use App\Models\AcademicPeriod;

class StudentGroupController extends Controller
{
    public function index()
    {
        $studentGroups = StudentGroup::with('academicPeriod')->paginate(10);
        return view('academic.student-groups.index', compact('studentGroups'));
    }

    public function create()
    {
        $academicPeriods = AcademicPeriod::where('is_active', true)->get();
        return view('academic.student-groups.create', compact('academicPeriods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'student_count' => 'required|integer|min:1',
            'special_characteristics' => 'nullable|string',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'is_active' => 'boolean',
        ]);

        StudentGroup::create($validated);

        return redirect()->route('academic.student-groups.index')
            ->with('success', 'Grupo de estudiantes creado exitosamente.');
    }

    public function show(StudentGroup $studentGroup)
    {
        return view('academic.student-groups.show', compact('studentGroup'));
    }

    public function edit(StudentGroup $studentGroup)
    {
        $academicPeriods = AcademicPeriod::where('is_active', true)->get();
        return view('academic.student-groups.edit', compact('studentGroup', 'academicPeriods'));
    }

    public function update(Request $request, StudentGroup $studentGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'student_count' => 'required|integer|min:1',
            'special_characteristics' => 'nullable|string',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'is_active' => 'boolean',
        ]);

        $studentGroup->update($validated);

        return redirect()->route('academic.student-groups.index')
            ->with('success', 'Grupo de estudiantes actualizado exitosamente.');
    }

    public function destroy(StudentGroup $studentGroup)
    {
        $studentGroup->delete();

        return redirect()->route('academic.student-groups.index')
            ->with('success', 'Grupo de estudiantes eliminado exitosamente.');
    }
}