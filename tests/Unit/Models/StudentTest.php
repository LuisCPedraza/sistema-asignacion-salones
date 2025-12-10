<?php

namespace Tests\Unit\Models;

use App\Models\Student;
use App\Modules\GestionAcademica\Models\StudentGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_student()
    {
        $group = StudentGroup::factory()->create();

        $student = Student::create([
            'codigo' => 'EST-2024-001',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan.perez@test.com',
            'telefono' => '555-1234',
            'group_id' => $group->id,
            'estado' => 'activo',
            'observaciones' => 'Estudiante regular',
        ]);

        $this->assertDatabaseHas('students', [
            'codigo' => 'EST-2024-001',
            'email' => 'juan.perez@test.com',
            'estado' => 'activo',
        ]);
    }

    /** @test */
    public function it_requires_unique_codigo()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $group = StudentGroup::factory()->create();

        Student::create([
            'codigo' => 'EST-001',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@test.com',
            'group_id' => $group->id,
        ]);

        Student::create([
            'codigo' => 'EST-001', // Duplicado
            'nombre' => 'María',
            'apellido' => 'García',
            'email' => 'maria@test.com',
            'group_id' => $group->id,
        ]);
    }

    /** @test */
    public function it_requires_unique_email()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $group = StudentGroup::factory()->create();

        Student::create([
            'codigo' => 'EST-001',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'estudiante@test.com',
            'group_id' => $group->id,
        ]);

        Student::create([
            'codigo' => 'EST-002',
            'nombre' => 'María',
            'apellido' => 'García',
            'email' => 'estudiante@test.com', // Duplicado
            'group_id' => $group->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_group()
    {
        $group = StudentGroup::factory()->create(['name' => 'Grupo A']);
        
        $student = Student::factory()->create([
            'group_id' => $group->id,
        ]);

        $this->assertInstanceOf(StudentGroup::class, $student->group);
        $this->assertEquals('Grupo A', $student->group->name);
    }

    /** @test */
    public function it_has_nombre_completo_attribute()
    {
        $student = Student::factory()->create([
            'nombre' => 'Juan Carlos',
            'apellido' => 'Pérez García',
        ]);

        $this->assertEquals('Juan Carlos Pérez García', $student->nombre_completo);
    }

    /** @test */
    public function it_can_scope_active_students()
    {
        $group = StudentGroup::factory()->create();

        Student::factory()->create(['group_id' => $group->id, 'estado' => 'activo']);
        Student::factory()->create(['group_id' => $group->id, 'estado' => 'activo']);
        Student::factory()->create(['group_id' => $group->id, 'estado' => 'inactivo']);
        Student::factory()->create(['group_id' => $group->id, 'estado' => 'retirado']);

        $activeStudents = Student::activos()->get();

        $this->assertCount(2, $activeStudents);
    }

    /** @test */
    public function it_can_scope_students_by_group()
    {
        $group1 = StudentGroup::factory()->create();
        $group2 = StudentGroup::factory()->create();

        Student::factory()->count(3)->create(['group_id' => $group1->id]);
        Student::factory()->count(2)->create(['group_id' => $group2->id]);

        $group1Students = Student::deGrupo($group1->id)->get();
        $group2Students = Student::deGrupo($group2->id)->get();

        $this->assertCount(3, $group1Students);
        $this->assertCount(2, $group2Students);
    }

    /** @test */
    public function it_has_default_estado_activo()
    {
        $group = StudentGroup::factory()->create();

        $student = Student::create([
            'codigo' => 'EST-DEFAULT',
            'nombre' => 'Default',
            'apellido' => 'Estado',
            'email' => 'default@test.com',
            'telefono' => null,
            'group_id' => $group->id,
            // No enviamos estado para que aplique el default de DB
        ]);

        $this->assertEquals('activo', $student->fresh()->estado);
    }

    /** @test */
    public function it_can_have_optional_telefono_and_observaciones()
    {
        $student = Student::factory()->create([
            'telefono' => null,
            'observaciones' => null,
        ]);

        $this->assertNull($student->telefono);
        $this->assertNull($student->observaciones);
    }

    /** @test */
    public function it_cascades_delete_when_group_is_deleted()
    {
        $group = StudentGroup::factory()->create();
        $student = Student::factory()->create(['group_id' => $group->id]);

        $this->assertDatabaseHas('students', ['id' => $student->id]);

        $group->delete();

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
