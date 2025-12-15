<?php

namespace App\Modules\Admin\Services;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\Teacher;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Obtener estadísticas generales del sistema
     */
    public function getGeneralStatistics()
    {
        return [
            'total_groups' => StudentGroup::where('is_active', true)->count(),
            'total_classrooms' => Classroom::where('is_active', true)->count(),
            'total_teachers' => Teacher::where('is_active', true)->count(),
            'total_assignments' => Assignment::count(),
            'avg_quality_score' => Assignment::avg('score') ?? 0,
            'assignments_with_good_quality' => Assignment::where('score', '>=', 0.8)->count(),
        ];
    }

    /**
     * Obtener utilización de salones
     */
    public function getClassroomUtilization($careerId = null, $semesterId = null)
    {
        $query = Assignment::with('classroom', 'group')
            ->selectRaw('classroom_id, COUNT(*) as assignment_count, AVG(score) as avg_score');

            if ($careerId) {
                $query->whereHas('group', function ($q) use ($careerId) {
                    $q->whereHas('semester', function ($sq) use ($careerId) {
                        $sq->where('career_id', $careerId);
                    });
                });
        }

        if ($semesterId) {
            $query->whereHas('group', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            });
        }

        $utilization = $query->groupBy('classroom_id')->get();

        return $utilization->map(function ($item) {
            return [
                'classroom_id' => $item->classroom_id,
                'classroom_name' => $item->classroom->name ?? 'N/A',
                'classroom_code' => $item->classroom->code ?? 'N/A',
                'classroom_capacity' => $item->classroom->capacity ?? 0,
                'assignment_count' => $item->assignment_count,
                'avg_score' => round($item->avg_score * 100, 1),
                'utilization_percentage' => $item->classroom ? 
                    round(($item->assignment_count / 30) * 100, 1) : 0, // Asumiendo 30 bloques por semestre
            ];
        })->sortByDesc('assignment_count');
    }

    /**
     * Obtener utilización por profesor
     */
    public function getTeacherUtilization($careerId = null, $semesterId = null)
    {
        $query = Assignment::with('teacher', 'group')
            ->selectRaw('teacher_id, COUNT(*) as assignment_count, AVG(score) as avg_score');

            if ($careerId) {
                $query->whereHas('group', function ($q) use ($careerId) {
                    $q->whereHas('semester', function ($sq) use ($careerId) {
                        $sq->where('career_id', $careerId);
                    });
                });
        }

        if ($semesterId) {
            $query->whereHas('group', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            });
        }

        $utilization = $query->groupBy('teacher_id')->get();

        return $utilization->map(function ($item) {
            $teacher = $item->teacher;
            $teacherName = $teacher 
                ? trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) 
                : 'Sin asignar';
            
            return [
                'teacher_id' => $item->teacher_id,
                'teacher_name' => $teacherName ?: 'Sin nombre',
                'teacher_email' => $teacher->email ?? 'N/A',
                'teacher_code' => $teacher->id ? 'T-' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT) : 'N/A',
                'assignment_count' => $item->assignment_count,
                'avg_score' => round($item->avg_score * 100, 1),
            ];
        })->sortByDesc('assignment_count');
    }

    /**
     * Obtener estadísticas de grupos
     */
    public function getGroupStatistics($careerId = null, $semesterId = null)
    {
        $query = StudentGroup::where('is_active', true);

            if ($careerId) {
                $query->whereHas('semester', function ($sq) use ($careerId) {
                    $sq->where('career_id', $careerId);
                });
            }

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $groups = $query->get();

        return [
            'total_groups' => $groups->count(),
            'total_students' => $groups->sum('number_of_students'),
            'avg_group_size' => $groups->count() > 0 ? 
                round($groups->sum('number_of_students') / $groups->count(), 1) : 0,
            'groups_with_assignments' => Assignment::whereIn('student_group_id', $groups->pluck('id'))->distinct('student_group_id')->count(),
        ];
    }

    /**
     * Obtener distribución de calidad de asignaciones
     */
    public function getQualityDistribution($careerId = null, $semesterId = null)
    {
        $query = Assignment::with('group');

            if ($careerId) {
                $query->whereHas('group', function ($q) use ($careerId) {
                    $q->whereHas('semester', function ($sq) use ($careerId) {
                        $sq->where('career_id', $careerId);
                    });
                });
        }

        if ($semesterId) {
            $query->whereHas('group', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            });
        }

        $assignments = $query->get();
        $total = $assignments->count();

        if ($total === 0) {
            return [
                'excellent' => 0,
                'good' => 0,
                'fair' => 0,
                'poor' => 0,
            ];
        }

        return [
            'excellent' => round((($assignments->where('score', '>=', 0.9)->count()) / $total) * 100, 1),
            'good' => round((($assignments->where('score', '>=', 0.8)->where('score', '<', 0.9)->count()) / $total) * 100, 1),
            'fair' => round((($assignments->where('score', '>=', 0.7)->where('score', '<', 0.8)->count()) / $total) * 100, 1),
            'poor' => round((($assignments->where('score', '<', 0.7)->count()) / $total) * 100, 1),
        ];
    }

    /**
     * Obtener tendencias mensuales (simulado)
     */
    public function getMonthlyTrends($months = 6)
    {
        $trends = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Assignment::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $trends[] = [
                'month' => $date->format('M Y'),
                'assignments' => $count,
            ];
        }

        return $trends;
    }

    /**
     * Obtener conflictos detectados
     */
    public function getConflictStatistics($careerId = null, $semesterId = null)
    {
        $query = Assignment::with('group')
            ->whereNotNull('notes')
            ->where('notes', 'like', '%conflicto%');

            if ($careerId) {
                $query->whereHas('group', function ($q) use ($careerId) {
                    $q->whereHas('semester', function ($sq) use ($careerId) {
                        $sq->where('career_id', $careerId);
                    });
                });
        }

        if ($semesterId) {
            $query->whereHas('group', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            });
        }

        return [
            'total_conflicts' => $query->count(),
            'conflict_percentage' => Assignment::count() > 0 ?
                round(($query->count() / Assignment::count()) * 100, 1) : 0,
        ];
    }

    /**
     * Obtener reporte completo filtrado
     */
    public function getComprehensiveReport($careerId = null, $semesterId = null)
    {
        return [
            'general_statistics' => $this->getGeneralStatistics(),
            'classroom_utilization' => $this->getClassroomUtilization($careerId, $semesterId),
            'teacher_utilization' => $this->getTeacherUtilization($careerId, $semesterId),
            'group_statistics' => $this->getGroupStatistics($careerId, $semesterId),
            'quality_distribution' => $this->getQualityDistribution($careerId, $semesterId),
            'monthly_trends' => $this->getMonthlyTrends(6),
            'conflict_statistics' => $this->getConflictStatistics($careerId, $semesterId),
        ];
    }
}
