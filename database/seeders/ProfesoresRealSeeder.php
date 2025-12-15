<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfesoresRealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array de profesores del programa real
        $profesores = [
            // Profesor de ejemplo
            [
                'first_name' => 'Carlos',
                'last_name' => 'Mendoza',
                'email' => 'carlos.mendoza@universidad.edu',
                'phone' => '+57 300 123 4567',
                'specialty' => 'Programación',
                'specialties' => json_encode(['Programación', 'Software']),
                'curriculum' => 'Profesor de ejemplo. Disponible para diferentes asignaturas.',
                'years_experience' => 10,
                'academic_degree' => 'Maestría en Ingeniería de Software',
                'is_active' => true,
            ],

            // Profesores de Administración de Empresas 3845
            ['first_name' => 'ALVARO', 'last_name' => 'TORRES GRANJA', 'email' => 'alvaro.torres@universidad.edu', 'phone' => '+57 300 000 0001', 'specialty' => 'Economía', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JHON FREDY', 'last_name' => 'RODRIGUEZ MOLINA', 'email' => 'jhon.rodriguez@universidad.edu', 'phone' => '+57 300 000 0002', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MARTIN DE JESUS', 'last_name' => 'CALCEDO CUENCA', 'email' => 'martin.calcedo@universidad.edu', 'phone' => '+57 300 000 0003', 'specialty' => 'Derecho', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JENNIFER ANDREA', 'last_name' => 'RUIZ AGUIRRE', 'email' => 'jennifer.ruiz@universidad.edu', 'phone' => '+57 300 000 0004', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JULIÁN ENRIQUE', 'last_name' => 'CASTRO SEGURA', 'email' => 'julian.castro@universidad.edu', 'phone' => '+57 300 000 0005', 'specialty' => 'Informática', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ANA MARIA', 'last_name' => 'CARDONA GIRALDO', 'email' => 'ana.cardona@universidad.edu', 'phone' => '+57 300 000 0006', 'specialty' => 'Contabilidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JHON WALTER', 'last_name' => 'TORRES MEZA', 'email' => 'jhon.torres@universidad.edu', 'phone' => '+57 300 000 0007', 'specialty' => 'Español', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'DIANA YISEI', 'last_name' => 'GOYES VALENCIA', 'email' => 'diana.goyes@universidad.edu', 'phone' => '+57 300 000 0008', 'specialty' => 'Humanidades', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ERNESTO', 'last_name' => 'LOZANO GARCÍA', 'email' => 'ernesto.lozano@universidad.edu', 'phone' => '+57 300 000 0009', 'specialty' => 'Economía', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ROBERTO LUCIEN', 'last_name' => 'LARMAT GONZALEZ', 'email' => 'roberto.larmat@universidad.edu', 'phone' => '+57 300 000 0010', 'specialty' => 'Humanidades', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CESAR AUGUSTO', 'last_name' => 'SAAVEDRA QUINTERO', 'email' => 'cesar.saavedra@universidad.edu', 'phone' => '+57 300 000 0011', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'HUBERNEY', 'last_name' => 'LONDÓDIO HERMÁNDEZ', 'email' => 'huberney.londorio@universidad.edu', 'phone' => '+57 300 000 0012', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ANA CAROLINA', 'last_name' => 'BERMUDIZ PAREDES', 'email' => 'ana.bermudiz@universidad.edu', 'phone' => '+57 300 000 0013', 'specialty' => 'Inglés', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'SARA OFIR', 'last_name' => 'PIEDRAHITA PEREZ', 'email' => 'sara.piedrahita@universidad.edu', 'phone' => '+57 300 000 0014', 'specialty' => 'Derecho', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MAURICIO', 'last_name' => 'BARRERA REBELLON', 'email' => 'mauricio.barrera@universidad.edu', 'phone' => '+57 300 000 0015', 'specialty' => 'Estadística', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JUAN CAMILO', 'last_name' => 'ZUÑIGA PRADO', 'email' => 'juan.zuniga@universidad.edu', 'phone' => '+57 300 000 0016', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JOSE LUIS', 'last_name' => 'DONCEL GUIAVITA', 'email' => 'jose.doncel@universidad.edu', 'phone' => '+57 300 000 0017', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'OSCAR FERNANDO', 'last_name' => 'MARTINEZ MAYOR', 'email' => 'oscar.martinez@universidad.edu', 'phone' => '+57 300 000 0018', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JOHN ALEJANDRO', 'last_name' => 'LEDESMA', 'email' => 'john.ledesma@universidad.edu', 'phone' => '+57 300 000 0019', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CESAR', 'last_name' => 'ROLDAN SOTO', 'email' => 'cesar.roldan@universidad.edu', 'phone' => '+57 300 000 0020', 'specialty' => 'Mercadeo', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'YANETH', 'last_name' => 'GOMEZ NOVOA', 'email' => 'yaneth.gomez@universidad.edu', 'phone' => '+57 300 000 0021', 'specialty' => 'Contabilidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ISABEL CRISTINA', 'last_name' => 'ACEVEDO SAMABRÍA', 'email' => 'isabel.acevedo@universidad.edu', 'phone' => '+57 300 000 0022', 'specialty' => 'Inglés', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CARLOS ALBERTO', 'last_name' => 'ROJAS TREJOS', 'email' => 'carlos.rojas@universidad.edu', 'phone' => '+57 300 000 0023', 'specialty' => 'Operaciones', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JULIO HERNANDO', 'last_name' => 'LOZANO JIMENEZ', 'email' => 'julio.lozano@universidad.edu', 'phone' => '+57 300 000 0024', 'specialty' => 'Finanzas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ALVARO JAVIER', 'last_name' => 'BARERO APONTE', 'email' => 'alvaro.barero@universidad.edu', 'phone' => '+57 300 000 0025', 'specialty' => 'Innovación', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ALEXANDER', 'last_name' => 'HURTADO LOPEZ', 'email' => 'alexander.hurtado@universidad.edu', 'phone' => '+57 300 000 0026', 'specialty' => 'RSE', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'HOOVER', 'last_name' => 'HELAGO GAVIRIA', 'email' => 'hoover.helago@universidad.edu', 'phone' => '+57 300 000 0027', 'specialty' => 'Humanidades', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ANDREA', 'last_name' => 'PALOMINO SALCEDO', 'email' => 'andrea.palomino@universidad.edu', 'phone' => '+57 300 000 0028', 'specialty' => 'Sistemas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'FRANCY JANED', 'last_name' => 'SARINA ROJAS', 'email' => 'francy.sarina@universidad.edu', 'phone' => '+57 300 000 0029', 'specialty' => 'Gestión Humana', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'HECTOR JORGE', 'last_name' => 'SALDARRIAGA', 'email' => 'hector.saldarriaga@universidad.edu', 'phone' => '+57 300 000 0030', 'specialty' => 'Finanzas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ARMANDO', 'last_name' => 'SOLUCYO ROJAS REÑAZ', 'email' => 'armando.solucyo@universidad.edu', 'phone' => '+57 300 000 0031', 'specialty' => 'Humanidades', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MANCY FABIOLA', 'last_name' => 'GOMEZ DIAZ', 'email' => 'mancy.gomez@universidad.edu', 'phone' => '+57 300 000 0032', 'specialty' => 'Administración', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MAURICIO ALEJANDRO', 'last_name' => 'BUITRAGO SOTO', 'email' => 'mauricio.buitrago@universidad.edu', 'phone' => '+57 300 000 0033', 'specialty' => 'Logística', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ALEJANDRO', 'last_name' => 'MEDINA MARIN', 'email' => 'alejandro.medina@universidad.edu', 'phone' => '+57 300 000 0034', 'specialty' => 'Investigación', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'OSCAR HUMBERTO', 'last_name' => 'GAVIRIA ARANA', 'email' => 'oscar.gaviria@universidad.edu', 'phone' => '+57 300 000 0035', 'specialty' => 'Comercio', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JHON EDGARDO', 'last_name' => 'VALENCIA MONTAÑO', 'email' => 'jhon.valencia@universidad.edu', 'phone' => '+57 300 000 0036', 'specialty' => 'Calidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JUAN CARLOS', 'last_name' => 'GALEANO GARIBELLO', 'email' => 'juan.galeano@universidad.edu', 'phone' => '+57 300 000 0037', 'specialty' => 'Sistemas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],

            // Profesores adicionales de Contaduría Pública 3841
            ['first_name' => 'CARLOS ALBERTO', 'last_name' => 'GARCÍA GUZMÁN', 'email' => 'carlos.garcia@universidad.edu', 'phone' => '+57 300 000 0038', 'specialty' => 'Educación Física', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ISABEL CRISTINA', 'last_name' => 'GUZMÁN LOPEZ', 'email' => 'isabel.guzman@universidad.edu', 'phone' => '+57 300 000 0039', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JULIAN ORLANDO', 'last_name' => 'CARMONA BETANCOURT', 'email' => 'julian.carmona@universidad.edu', 'phone' => '+57 300 000 0040', 'specialty' => 'Informática', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'NELSY', 'last_name' => 'DUQUE CARVAJAL', 'email' => 'nelsy.duque@universidad.edu', 'phone' => '+57 300 000 0041', 'specialty' => 'Contabilidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'DIEGO FERNANDO', 'last_name' => 'RODRIGUEZ JIMENEZ', 'email' => 'diego.rodriguez@universidad.edu', 'phone' => '+57 300 000 0042', 'specialty' => 'Contabilidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CLAUDIA LILIANA', 'last_name' => 'SILVA CABREBA', 'email' => 'claudia.silva@universidad.edu', 'phone' => '+57 300 000 0043', 'specialty' => 'Español', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'WILSON', 'last_name' => 'ESCOBAR NAVIA', 'email' => 'wilson.escobar@universidad.edu', 'phone' => '+57 300 000 0044', 'specialty' => 'Economía', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CARLOS HÉCTOR', 'last_name' => 'CALCEDO BALANTA', 'email' => 'carlos.calcedo@universidad.edu', 'phone' => '+57 300 000 0045', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MARCO ANTONIO', 'last_name' => 'VILLAFAÑE VIDAL', 'email' => 'marco.villafane@universidad.edu', 'phone' => '+57 300 000 0046', 'specialty' => 'Derecho', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JULIÁN DAVID', 'last_name' => 'CLAVID RAYO', 'email' => 'julian.clavid@universidad.edu', 'phone' => '+57 300 000 0047', 'specialty' => 'Contabilidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'NOHRA LUCIA', 'last_name' => 'CASTRILLON LLANOS', 'email' => 'nohra.castrillon@universidad.edu', 'phone' => '+57 300 000 0048', 'specialty' => 'Inglés', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'HERMANDO', 'last_name' => 'MORALES TULIO', 'email' => 'hermando.morales@universidad.edu', 'phone' => '+57 300 000 0049', 'specialty' => 'Humanidades', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'LUBIAN HERNANDO', 'last_name' => 'MORENO MINA', 'email' => 'lubian.moreno@universidad.edu', 'phone' => '+57 300 000 0050', 'specialty' => 'Humanidades', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ISABELINO', 'last_name' => 'LASSO CUTIVA', 'email' => 'isabelino.lasso@universidad.edu', 'phone' => '+57 300 000 0051', 'specialty' => 'Impuestos', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'VÍCTOR ANDRÉS', 'last_name' => 'PAZMIÑO LUNA', 'email' => 'victor.pazmino@universidad.edu', 'phone' => '+57 300 000 0052', 'specialty' => 'Contabilidad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'EDWIN', 'last_name' => 'LARGO CAÑAVERAL', 'email' => 'edwin.largo@universidad.edu', 'phone' => '+57 300 000 0053', 'specialty' => 'Economía', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],

            // Profesores adicionales de Comercio Exterior 3857
            ['first_name' => 'CARLOS ANDRES', 'last_name' => 'CUARTAS PEREZ', 'email' => 'carlos.cuartas@universidad.edu', 'phone' => '+57 300 000 0054', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'RICARDO', 'last_name' => 'BUITRAGO UMAÑA', 'email' => 'ricardo.buitrago@universidad.edu', 'phone' => '+57 300 000 0055', 'specialty' => 'Sistemas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'HECTOR LEONARDO', 'last_name' => 'ANGULO CASTILLO', 'email' => 'hector.angulo@universidad.edu', 'phone' => '+57 300 000 0056', 'specialty' => 'Inglés', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CLAUDIA MARCEJA', 'last_name' => 'AIMS GONZALEZ', 'email' => 'claudia.aims@universidad.edu', 'phone' => '+57 300 000 0057', 'specialty' => 'Español', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ANGELA MARIA', 'last_name' => 'LOPEZ REYES', 'email' => 'angela.lopez@universidad.edu', 'phone' => '+57 300 000 0058', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'WILLIAM', 'last_name' => 'GOMEZ VALENCIA', 'email' => 'william.gomez@universidad.edu', 'phone' => '+57 300 000 0059', 'specialty' => 'Sistemas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'OBESY VIVIANA', 'last_name' => 'REGIONO CARRERA', 'email' => 'obesy.regiono@universidad.edu', 'phone' => '+57 300 000 0060', 'specialty' => 'Economía', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'GIZMAN LOPEZ', 'last_name' => 'ISABEL CRISTINA', 'email' => 'gizman.lopez@universidad.edu', 'phone' => '+57 300 000 0061', 'specialty' => 'Estadística', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MARTIN DE JESUS', 'last_name' => 'CALCEO CLUNERA', 'email' => 'martin.calceo@universidad.edu', 'phone' => '+57 300 000 0062', 'specialty' => 'Derecho', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JUAN CARLOS', 'last_name' => 'MONCADA RENDON', 'email' => 'juan.moncada@universidad.edu', 'phone' => '+57 300 000 0063', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CLAUDIA LILIANA', 'last_name' => 'SUVA CARRERA', 'email' => 'claudia.suva@universidad.edu', 'phone' => '+57 300 000 0064', 'specialty' => 'Vida Universitaria', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'GIANCARLO', 'last_name' => 'LIBREROS LONDONO', 'email' => 'giancarlo.libreros@universidad.edu', 'phone' => '+57 300 000 0065', 'specialty' => 'Estadística', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'NIDIA STEFANIA', 'last_name' => 'CASTELLANOS GARZÓN', 'email' => 'nidia.castellanos@universidad.edu', 'phone' => '+57 300 000 0066', 'specialty' => 'Derecho', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CLAUDIA MILENA', 'last_name' => 'GOMEZ ZULUAGA', 'email' => 'claudia.zuluaga@universidad.edu', 'phone' => '+57 300 000 0067', 'specialty' => 'Mercadeo', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'SARA MARÍA', 'last_name' => 'LEMIR AGUIRRE', 'email' => 'sara.lemir@universidad.edu', 'phone' => '+57 300 000 0068', 'specialty' => 'Comercio', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'DESY VIVIANA', 'last_name' => 'PERDOMO CABRERA', 'email' => 'desy.perdomo@universidad.edu', 'phone' => '+57 300 000 0069', 'specialty' => 'Competitividad', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'MARIANA CAROLINA', 'last_name' => 'HERMÁNDEZ PALACIO', 'email' => 'mariana.hernandez@universidad.edu', 'phone' => '+57 300 000 0070', 'specialty' => 'Finanzas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'JHOSE LUIS', 'last_name' => 'UNAS GOMEZ', 'email' => 'jhose.unas@universidad.edu', 'phone' => '+57 300 000 0071', 'specialty' => 'Sistemas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'CLAUDIA MARCELA', 'last_name' => 'ARIAS GONZALEZ', 'email' => 'claudia.arias@universidad.edu', 'phone' => '+57 300 000 0072', 'specialty' => 'Ética', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'HAROLD', 'last_name' => 'ALVAREZ CORTES', 'email' => 'harold.alvarez@universidad.edu', 'phone' => '+57 300 000 0073', 'specialty' => 'Inglés', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],

            // Profesores de Tecnología en Desarrollo de Software 2724
            ['first_name' => 'ESTEBAN ELIAS', 'last_name' => 'GIRALDO SALAZAR', 'email' => 'esteban.giraldo@universidad.edu', 'phone' => '+57 300 000 0074', 'specialty' => 'Matemáticas', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'FRANCISCO JAVIER', 'last_name' => 'VILLEGAS MONDRAGON', 'email' => 'francisco.villegas2@universidad.edu', 'phone' => '+57 300 000 0075', 'specialty' => 'Educación Física', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ALEJANDRO', 'last_name' => 'MEDINA MARÍN', 'email' => 'alejandro.medina2@universidad.edu', 'phone' => '+57 300 000 0076', 'specialty' => 'Vida Universitaria', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'TATIANA', 'last_name' => 'SERNA IMBACHI', 'email' => 'tatiana.serna@universidad.edu', 'phone' => '+57 300 000 0077', 'specialty' => 'Tecnología', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'YAZMIN HELENA', 'last_name' => 'RIAÑO BARON', 'email' => 'yazmin.riano@universidad.edu', 'phone' => '+57 300 000 0078', 'specialty' => 'Talleres', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'LINO ALEXANDER', 'last_name' => 'SINISTERRA ASPRILLA', 'email' => 'lino.sinisterra@universidad.edu', 'phone' => '+57 300 000 0079', 'specialty' => 'Programación', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'DANIEL ESTEBAN', 'last_name' => 'GAVIRIA CLAVIJO', 'email' => 'daniel.gaviria@universidad.edu', 'phone' => '+57 300 000 0084', 'specialty' => 'Programación', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'FABIAN ALBERTO', 'last_name' => 'RAMIREZ RODRIGUEZ', 'email' => 'fabian.ramirez@universidad.edu', 'phone' => '+57 300 000 0085', 'specialty' => 'Programación', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ANA MERCEDES', 'last_name' => 'GIRALDO SALAZAR', 'email' => 'anamercedes.giraldo@universidad.edu', 'phone' => '+57 300 000 0087', 'specialty' => 'Ambiental', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'GUSTAVO ADOLFO', 'last_name' => 'OSORIO', 'email' => 'gustavo.osorio@universidad.edu', 'phone' => '+57 300 000 0088', 'specialty' => 'Desarrollo', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
            ['first_name' => 'ANDRÉS', 'last_name' => 'GARZON VALENCIA', 'email' => 'andres.garzon@universidad.edu', 'phone' => '+57 300 000 0089', 'specialty' => 'Análisis', 'years_experience' => 5, 'academic_degree' => 'Pregrado'],
        ];

        foreach ($profesores as $profesor) {
            // Asegurar que los campos obligatorios tengan valores
            $profesor['specialties'] = json_encode([$profesor['specialty']]);
            $profesor['curriculum'] = $profesor['curriculum'] ?? 'Profesor de ' . $profesor['specialty'];
            $profesor['academic_degree'] = $profesor['academic_degree'] ?? 'Pregrado';
            $profesor['is_active'] = true;

            Teacher::updateOrCreate(
                [
                    'first_name' => $profesor['first_name'],
                    'last_name' => $profesor['last_name']
                ],
                $profesor
            );
        }
    }
}
