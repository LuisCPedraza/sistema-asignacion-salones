<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Career;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener carreras
        $careerAdminEmpresas = Career::where('code', '3845')->first();
        $careerContaduria = Career::where('code', '3841')->first();
        $careerComercioExterior = Career::where('code', '3857')->first();
        $careerSoftware = Career::where('code', '2724')->first();

        // ========== ADMINISTRACIÓN DE EMPRESAS 3845 ==========
        $subjectsAdminEmpresas = [
            // Semestre 1
            ['code' => '801002C', 'name' => 'FUNDAMENTOS DE ECONOMÍA Y COMERCIO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801003C', 'name' => 'CÁLCULO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801042C', 'name' => 'INTRODUCCIÓN AL DERECHO Y CONSTITUCIÓN POLÍTICA', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801043C', 'name' => 'PROCESO ADMINISTRATIVO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801044C', 'name' => 'TALLER DE HABILIDADES INFORMÁTICAS PARA LA GESTIÓN', 'semester' => 1, 'credit_hours' => 2, 'cupo' => 30],
            ['code' => '802004C', 'name' => 'FUNDAMENTOS DE CONTABILIDAD', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            // Semestre 2
            ['code' => '204133C', 'name' => 'COMPRENSIÓN Y PRODUCCIÓN DE TEXTOS ACADÉMICOS GENERALES', 'semester' => 2, 'credit_hours' => 2, 'cupo' => 18],
            ['code' => '608032C', 'name' => 'HABILIDADES PARA LA VIDA: DESCUBRIENDO EL POTENCIAL DEL SER EMOCIONAL, AFECTIVO, COGNITIVO Y SOCIAL', 'semester' => 2, 'credit_hours' => 2, 'cupo' => 25],
            ['code' => '801017C', 'name' => 'ÁLGEBRA LINEAL', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801058C', 'name' => 'MACROECONOMÍA Y COYUNTURA', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801078C', 'name' => 'CIENCIAS HUMANAS', 'semester' => 2, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801079C', 'name' => 'DISEÑO ORGANIZACIONAL', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801082C', 'name' => 'PENSAMIENTO CLÁSICO DE LA ADMINISTRACIÓN Y LAS ORGANIZACIONES', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 25],
            // Semestre 3
            ['code' => '204025C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS I', 'semester' => 3, 'credit_hours' => 2, 'cupo' => 30],
            ['code' => '801058C', 'name' => 'LEGISLACIÓN COMERCIAL', 'semester' => 3, 'credit_hours' => 2, 'cupo' => 25],
            ['code' => '801088C', 'name' => 'ESTADISTICA I PARA LAS CIENCIAS DE LA ADMINISTRACIÓN', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801089C', 'name' => 'ECONOMÍA DE EMPRESA', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801091C', 'name' => 'PENSAMIENTO CONTEMPORÁNEO DE LA ADMINISTRACIÓN Y LAS ORGANIZACIONES', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801092C', 'name' => 'ORGANIZACIÓN Y SOCIEDAD', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '802013C', 'name' => 'MATEMÁTICA PARA FINANZAS', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 30],
            // Semestre 4
            ['code' => '204024C', 'name' => 'LECTURA CRÍTICA', 'semester' => 4, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '204026C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS II', 'semester' => 4, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801099C', 'name' => 'LEGISLACIÓN LABORAL', 'semester' => 4, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801104C', 'name' => 'ESTADISTICA II PARA LAS CIENCIAS DE LA ADMINISTRACIÓN', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801105C', 'name' => 'MODELOS Y SIMULACIÓN DE DATOS PARA LAS ORGANIZACIONES', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801111C', 'name' => 'MERCADEO', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802028C', 'name' => 'COSTOS Y PRESUPUESTOS GERENCIALES', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 5
            ['code' => '204027C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS III', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 40],
            ['code' => '801095C', 'name' => 'INVESTIGACIÓN Y GESTION DE OPERACIONES', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801113C', 'name' => 'ANÁLISIS INTEGRADO DEL ENTORNO', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801114C', 'name' => 'GESTIÓN DEL CONOCIMIENTO E INNOVACIÓN', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801115C', 'name' => 'INVESTIGACIÓN DE MERCADOS', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801116C', 'name' => 'RESPONSABILIDAD SOCIAL EMPRESARIAL Y SOSTENIBILIDAD', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801126C', 'name' => 'HUMANISMO, PENSAMIENTO ADMINISTRATIVO Y ORGANIZACIONES', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            // Semestre 6
            ['code' => '204028C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS IV', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801026C', 'name' => 'SISTEMAS DE INFORMACIÓN GERENCIAL', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801135C', 'name' => 'GESTIÓN DE BIENES Y SERVICIOS', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801134C', 'name' => 'LEADERSHIP', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801143C', 'name' => 'CREACIÓN E INTRODUCCIÓN DE PRODUCTOS', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802051C', 'name' => 'ADMINISTRACIÓN Y GESTIÓN FINANCIERA', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 7
            ['code' => '204124C', 'name' => 'NARRATIVAS DE VIDA', 'semester' => 7, 'credit_hours' => 2, 'cupo' => 25],
            ['code' => '801124C', 'name' => 'GESTIÓN HUMANA - PAE', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801157C', 'name' => 'INTELIGENCIA DE NEGOCIOS Y ANALÍTICA DE DATOS PARA LA GESTIÓN', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801156C', 'name' => 'NEGOCIACIÓN Y TOMA DE DECISIONES', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801158C', 'name' => 'GESTIÓN DE LA CADENA DE SUMINISTROS', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '802041C', 'name' => 'EVALUACIÓN FINANCIERA DE PROYECTOS', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 30],
        ];

        foreach ($subjectsAdminEmpresas as $subject) {
            $this->createSubject($subject, $careerAdminEmpresas);
        }

        // ========== CONTADURÍA PÚBLICA 3841 ==========
        $subjectsContaduria = [
            // Semestre 1
            ['code' => '404002C', 'name' => 'DEPORTE Y SALUD', 'semester' => 1, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801003C', 'name' => 'CÁLCULO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801044C', 'name' => 'INTRODUCCIÓN AL DERECHO Y CONSTITUCIÓN POLÍTICA', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802004C', 'name' => 'TALLER DE HABILIDADES INFORMÁTICAS PARA LA GESTIÓN', 'semester' => 1, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '802006C', 'name' => 'FUNDAMENTOS DE CONTABILIDAD', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802008C', 'name' => 'INTRODUCCIÓN A LA CONTADURIA PÚBLICA', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 2
            ['code' => '204133C', 'name' => 'COMPRENSIÓN Y PRODUCCIÓN DE TEXTOS ACADÉMICOS GENERALES', 'semester' => 2, 'credit_hours' => 2, 'cupo' => 25],
            ['code' => '801002C', 'name' => 'FUNDAMENTOS DE ECONOMÍA Y COMERCIO', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801017C', 'name' => 'ÁLGEBRA LINEAL', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801073C', 'name' => 'LEGISLACIÓN COMERCIAL I', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '802013C', 'name' => 'MATEMÁTICA PARA FINANZAS', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '802014C', 'name' => 'CONTABILIDAD DE LOS RECURSOS Y OBLIGACIONES DE CORTO PLAZO', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 30],
            // Semestre 3
            ['code' => '204025C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS I', 'semester' => 3, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801048C', 'name' => 'PROCESO ADMINISTRATIVO', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801069C', 'name' => 'MACROECONOMÍA Y COYUNTURA', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801074C', 'name' => 'LEGISLACIÓN COMERCIAL II', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801083C', 'name' => 'ESTADISTICA I PARA LAS CIENCIAS DE LA ADMINISTRACIÓN', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801099C', 'name' => 'LEGISLACIÓN LABORAL', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802015C', 'name' => 'CONTABILIDAD DE LOS RECURSOS Y OBLIGACIONES DE LARGO PLAZO', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802016C', 'name' => 'Y PATRIMONIO', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 5
            ['code' => '204027C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS III', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801078C', 'name' => 'CIENCIAS HUMANAS', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801126C', 'name' => 'HUMANISMO, PENSAMIENTO ADMINISTRATIVO Y ORGANIZACIONES', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '802007C', 'name' => 'CONTABILIDAD PÚBLICA', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802026C', 'name' => 'COSTOS', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802029C', 'name' => 'CONTABILIDADES ESPECIALES I', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802032C', 'name' => 'FUNDAMENTOS DE CONTROL, AUDITORIA Y ASEGURAMIENTO', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 6
            ['code' => '204028C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS IV', 'semester' => 6, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '802039C', 'name' => 'AUDITORIA, RIESGOS Y ASEGURAMIENTO', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802040C', 'name' => 'CONTABILIDADES ESPECIALES II', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802042C', 'name' => 'SEMINARIO DE TEORÍA CONTABLE', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802047C', 'name' => 'IMPUESTO A LA RENTA Y COMPLEMENTARIOS, IMPUESTO AL PATRIMONIO', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802049C', 'name' => 'PRESUPUESTOS', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '802056C', 'name' => 'COSTOS II', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
        ];

        foreach ($subjectsContaduria as $subject) {
            $this->createSubject($subject, $careerContaduria);
        }

        // ========== COMERCIO EXTERIOR 3857 ==========
        $subjectsComercioExterior = [
            // Semestre 1
            ['code' => '801002C', 'name' => 'FUNDAMENTOS DE ECONOMÍA Y COMERCIO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801003C', 'name' => 'CÁLCULO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '80104AC', 'name' => 'INTRODUCCIÓN AL DERECHO Y CONSTITUCIÓN POLÍTICA', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '80104BC', 'name' => 'PROCESO ADMINISTRATIVO', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '80104CC', 'name' => 'TALLER DE HABILIDADES INFORMÁTICAS PARA LA GESTIÓN', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 40],
            ['code' => '802004C', 'name' => 'FUNDAMENTOS DE CONTABILIDAD', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            // Semestre 2
            ['code' => '204025C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS I', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '204135C', 'name' => 'COMPRENSIÓN Y PRODUCCIÓN DE TEXTOS ACADÉMICOS GENERALES', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 55],
            ['code' => '801017C', 'name' => 'ÁLGEBRA LINEAL', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801026C', 'name' => 'SISTEMAS DE INFORMACIÓN GERENCIAL', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801058C', 'name' => 'LEGISLACIÓN COMERCIAL', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801059C', 'name' => 'MACROECONOMÍA Y COYUNTURA', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801071C', 'name' => 'RÉGIMEN ADUANERO', 'semester' => 2, 'credit_hours' => 3, 'cupo' => 35],
            // Semestre 3
            ['code' => '204026C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS II', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '404032C', 'name' => 'DEPORTE FORMATIVO', 'semester' => 3, 'credit_hours' => 2, 'cupo' => 35],
            ['code' => '801038C', 'name' => 'ESTADISTICA I PARA LAS CIENCIAS DE LA ADMINISTRACIÓN', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801088C', 'name' => 'ECONOMÍA DE EMPRESA', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801090C', 'name' => 'LEGISLACIÓN LABORAL', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801100C', 'name' => 'IMPORTACIONES', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '802013C', 'name' => 'MATEMÁTICA PARA FINANZAS', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 30],
            // Semestre 4
            ['code' => '204027C', 'name' => 'INGLÉS CON FINES GENERALES Y ACADÉMICOS III', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 40],
            ['code' => '402051C', 'name' => 'VIDA UNIVERSITARIA I: ENCUENTROS CON LA UNIVERSIDAD', 'semester' => 4, 'credit_hours' => 2, 'cupo' => 40],
            ['code' => '801078C', 'name' => 'CIENCIAS HUMANAS', 'semester' => 4, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '801102C', 'name' => 'TEORIA DE COMERCIO EXTERIOR', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 40],
            ['code' => '801103C', 'name' => 'EXPORTACIONES', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 40],
            ['code' => '801104C', 'name' => 'ESTADISTICA II PARA LAS CIENCIAS DE LA ADMINISTRACIÓN', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '801110C', 'name' => 'DERECHO INTERNACIONAL PÚBLICO Y PRIVADO', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 40],
            // Semestre 5
            ['code' => '204028C', 'name' => 'INGLES CON FINES GENERALES Y ACADÉMICOS IV', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '801095C', 'name' => 'INVESTIGACIÓN Y GESTIÓN DE OPERACIONES', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801111C', 'name' => 'MERCADEO', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 28],
            ['code' => '801121C', 'name' => 'TÉCNICAS DE NEGOCIACIÓN INTERNACIONAL', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 38],
            ['code' => '801122C', 'name' => 'ANÁLISIS DE LOS SISTEMAS DE PRODUCCIÓN DE BIENES Y SERVICIOS', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 38],
            ['code' => '801125C', 'name' => 'RÉGIMEN CAMBIARIO', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 38],
            ['code' => '801126C', 'name' => 'HUMANISMO, PENSAMIENTO ADMINISTRATIVO Y ORGANIZACIONES', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            // Semestre 6
            ['code' => '801135C', 'name' => 'COMPETITIVIDAD Y DESARROLLO ECONÓMICO REGIONAL', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 12],
            ['code' => '801137C', 'name' => 'ESTRATEGIAS Y NEGOCIOS INTERNACIONALES', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 12],
            ['code' => '801138C', 'name' => 'TRATADOS Y ACUERDOS COMERCIALES', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 12],
            ['code' => '801139C', 'name' => 'GEOCEONOMÍA', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 12],
            ['code' => '801140C', 'name' => 'LOGÍSTICA Y DISTRIBUCIÓN FÍSICA NACIONAL E INTERNACIONAL', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 12],
            ['code' => '802031C', 'name' => 'ANÁLISIS DE COSTOS Y PRESUPUESTOS', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 32],
            // Semestre 7
            ['code' => '208013C', 'name' => 'ÉTICA Y POLÍTICA', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '801154C', 'name' => 'ZONAS FRANCAS', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 40],
            ['code' => '801156C', 'name' => 'INVESTIGACIÓN DE MERCADOS INTERNACIONALES', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801157C', 'name' => 'INTELIGENCIA DE NEGOCIOS Y ANALÍTICA DE DATOS PARA LA GESTIÓN', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 25],
            ['code' => '801158C', 'name' => 'ECONOMETRÍA PARA COMERCIO EXTERIOR', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '801159C', 'name' => 'CONTRATACIÓN INTERNACIONAL', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '802051C', 'name' => 'ADMINISTRACIÓN Y GESTIÓN FINANCIERA', 'semester' => 7, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 8
            ['code' => '204163C', 'name' => 'COMPRENSIÓN Y PRODUCCIÓN DE TEXTOS ACADÉMICOS DE LAS DISCIPLINAS - DERECHO Y ADMINISTRACIÓN', 'semester' => 8, 'credit_hours' => 2, 'cupo' => 30],
            ['code' => '801136C', 'name' => 'EXPORTACIÓN DE SERVICIOS', 'semester' => 8, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '801192C', 'name' => 'INTEGRACIÓN ECONÓMICA Y BLOQUES COMERCIALES', 'semester' => 8, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '801194C', 'name' => 'CONSULTORIO EMPRESARIAL', 'semester' => 8, 'credit_hours' => 3, 'cupo' => 35],
            ['code' => '802041C', 'name' => 'EVALUACIÓN FINANCIERA DE PROYECTOS', 'semester' => 8, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '802078C', 'name' => 'FINANZAS INTERNACIONALES', 'semester' => 8, 'credit_hours' => 3, 'cupo' => 35],
        ];

        foreach ($subjectsComercioExterior as $subject) {
            $this->createSubject($subject, $careerComercioExterior);
        }

        // ========== TECNOLOGÍA EN DESARROLLO DE SOFTWARE 2724 ==========
        $subjectsSoftware = [
            // Semestre 1
            ['code' => '11103XC', 'name' => 'MATEMÁTICA BÁSICA', 'semester' => 1, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '40400XC', 'name' => 'DEPORTE Y SALUD', 'semester' => 1, 'credit_hours' => 1, 'cupo' => 30],
            ['code' => '70100XC', 'name' => 'INGERGÓN A LA VIDA UNIVERSITARIA', 'semester' => 1, 'credit_hours' => 1, 'cupo' => 30],
            ['code' => '70102XC', 'name' => 'FUNCIONACIÓN A LAS TECNOLOGÍA', 'semester' => 1, 'credit_hours' => 2, 'cupo' => 30],
            ['code' => '70200XC', 'name' => 'TALLER TECNOLÓGICO', 'semester' => 1, 'credit_hours' => 2, 'cupo' => 30],
            ['code' => '70001XC', 'name' => 'FUNDAMIENTOS DE PROGRAMACIÓN IMPERATIVA', 'semester' => 1, 'credit_hours' => 4, 'cupo' => 30],
            // Semestre 2
            ['code' => '11104XC', 'name' => 'CÁLCULO MONOVARIABLE', 'semester' => 2, 'credit_hours' => 4, 'cupo' => 25],
            ['code' => '11110XC', 'name' => 'ÁLGERRA LINEAL', 'semester' => 2, 'credit_hours' => 4, 'cupo' => 40],
            ['code' => '40402XC', 'name' => 'DEPORTE Y SALUD', 'semester' => 2, 'credit_hours' => 1, 'cupo' => 30],
            ['code' => '70203XC', 'name' => 'MATEMÁTICAS DISCRETAS I', 'semester' => 2, 'credit_hours' => 4, 'cupo' => 40],
            ['code' => '70301XC', 'name' => 'FUNDAMENTO DE PROGRAMACIÓN ORIENTADA A OBJETOS', 'semester' => 2, 'credit_hours' => 4, 'cupo' => 40],
            // Semestre 3
            ['code' => '70403XC', 'name' => 'INSELES CON FINES GENERALES Y ACADÉMICOS I', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70204XC', 'name' => 'SISTEMA OFENSIVO', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70205XC', 'name' => 'MATEMÁTICAS DISCRETAS II', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70300XC', 'name' => 'BASES DE DATOS', 'semester' => 3, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70302XC', 'name' => 'FUNDAMIENTOS DE PROGRAMACIÓN ORIENTADA A EVENTOS', 'semester' => 3, 'credit_hours' => 4, 'cupo' => 40],
            // Semestre 4
            ['code' => '70404XC', 'name' => 'INSELES CON FINES GENERALES Y ACADÉMICOS II', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70301XC', 'name' => 'BASES DE DATOS', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70400XC', 'name' => 'ANÁLISIS Y DESEÑO DE ALGORITMOS I', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70500XC', 'name' => 'FUNDAMIENTOS DE ERGIS', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 30],
            ['code' => '70101XC', 'name' => 'PROBABILIDAD Y ESTADÍSTICA', 'semester' => 4, 'credit_hours' => 3, 'cupo' => 30],
            // Semestre 5
            ['code' => '70501XC', 'name' => 'IMPACTOS AMBIENTALES', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '70502XC', 'name' => 'PROYECTO INTERRADOR I', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 15],
            ['code' => '70503XC', 'name' => 'DESARROLLO DE SOFTWARE II', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '70504XC', 'name' => 'CRERESCUBIDAD', 'semester' => 5, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '70505XC', 'name' => 'FUNDAMIENTOS DE ANÁLISIS Y CÁLCULO NUMÉRICO', 'semester' => 5, 'credit_hours' => 3, 'cupo' => 20],
            // Semestre 6
            ['code' => '70506XC', 'name' => 'SEMINARIO EN CONSTITUCIÓN, LEGISLACIÓN Y ÉTICA DE LA PROFESIÓN', 'semester' => 6, 'credit_hours' => 2, 'cupo' => 25],
            ['code' => '70507XC', 'name' => 'IMPACTOS AMBIENTALES', 'semester' => 6, 'credit_hours' => 2, 'cupo' => 20],
            ['code' => '70508XC', 'name' => 'PROYECTO INTERRADOR I', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 15],
            ['code' => '70509XC', 'name' => 'DESARROLLO DE SOFTWARE II', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 20],
            ['code' => '70510XC', 'name' => 'DESEÑO DE CONTENIDO PARA INTERFACES DE USUARIO', 'semester' => 6, 'credit_hours' => 3, 'cupo' => 25],
            // Semestre 7
            ['code' => '70511XC', 'name' => 'INNOVACIÓN Y EMPRENDIMIENTO EMPRESARIAL', 'semester' => 7, 'credit_hours' => 2, 'cupo' => 12],
            ['code' => '70512XM', 'name' => 'PRÁCTICA PROFESIONAL', 'semester' => 7, 'credit_hours' => 6, 'cupo' => 30],
            ['code' => '70513XM', 'name' => 'PRÁCTICA INVESTIGACIÓN I', 'semester' => 7, 'credit_hours' => 5, 'cupo' => 25],
        ];

        foreach ($subjectsSoftware as $subject) {
            $this->createSubject($subject, $careerSoftware);
        }
    }

    private function createSubject(array $data, Career $career): void
    {
        if (!$career) {
            return;
        }

        Subject::updateOrCreate(
            [
                'code' => $data['code'],
                    'semester_level' => $data['semester'],
                    'career_id' => $career->id
            ],
            [
                'name' => $data['name'],
                'semester_level' => $data['semester'],
                    'career_id' => $career->id,
                'credit_hours' => $data['credit_hours'],
                'lecture_hours' => $data['credit_hours'],
                'lab_hours' => 0,
                'specialty' => $career->name,
                'is_active' => true,
                'description' => 'Asignatura de ' . $career->name . ' - Semestre ' . $data['semester'],
            ]
        );
    }
}
