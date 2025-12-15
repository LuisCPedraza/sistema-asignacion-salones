<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\CourseSchedule;

class CourseScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $unmatched = [];
        $careers = [
            '3845' => $this->adminEmpresas(),
            '3841' => $this->contaduria(),
            '3857' => $this->comercioExterior(),
            '2724' => $this->software(),
        ];

        foreach ($careers as $code => $rows) {
            $career = Career::where('code',$code)->first();
            if (!$career) continue;

            foreach ($rows as $row) {
                $semester = Semester::where('career_id',$career->id)->where('number',$row['semestre'])->first();
                if (!$semester) continue;

                $subject = Subject::where('code',$row['codigo'])->where('career_id',$career->id)->where('semester_level',$row['semestre'])->first();
                if (!$subject) continue;

                $teacher = $this->findTeacherByFullName($row['docente']);
                if (!$teacher) {
                    $unmatched[] = $row['docente'];
                }

                CourseSchedule::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,
                    ],
                    [
                        'position_in_semester' => 1,
                        'required_teachers' => 1,
                        'teacher_id' => $teacher?->id,
                        'jornada' => $row['jornada'] ?? null,
                        'cupo' => $row['cupo'] ?? null,
                    ]
                );
            }
        }

        if (!empty($unmatched)) {
            $unique = array_values(array_unique($unmatched));
            $count = count($unique);
            info("Docentes no vinculados: {$count}");
            foreach ($unique as $name) {
                info("NO MATCH: {$name}");
            }
        }
    }

    private function findTeacherByFullName(string $fullName): ?Teacher
    {
        [$firstName, $lastName] = $this->normalizeName($fullName);
        if (!$firstName || !$lastName) return null;

        // Intento exacto en BD
        $teacher = Teacher::whereRaw('UPPER(first_name) = ?', [$firstName])
                          ->whereRaw('UPPER(last_name) = ?', [$lastName])
                          ->first();
        if ($teacher) return $teacher;

        // Fuzzy matching en memoria por tokens (mejor tolerancia a typos)
        $targetTokens = $this->tokenizeName($firstName . ' ' . $lastName);
        $best = null; $bestScore = 0.0;
        foreach (Teacher::all(['id','first_name','last_name']) as $t) {
            $cand = strtoupper($t->first_name . ' ' . $t->last_name);
            $candTokens = $this->tokenizeName($cand);
            $score = $this->jaccard($targetTokens, $candTokens);
            if ($score > $bestScore) { $bestScore = $score; $best = $t; }
        }
        return $bestScore >= 0.5 ? $best : null;
    }

    private function normalizeName(string $name): array
    {
        $n = strtoupper(trim($name));
        // limpiar puntuación final
        $n = preg_replace('/[\.;:,]+$/', '', $n);
        // mapa de equivalencias conocidas (errores tipográficos)
        $map = [
            // Correcciones comunes de tildes y typos
            'CALCEDO CUENCIA MARTIN DE JESUS' => 'CALCEDO CUENCA MARTIN DE JESUS',
            'CALCEO CLUNERA MARTIN DE JESUS' => 'CALCEDO CUENCA MARTIN DE JESUS',
            'LONDONO HERMÁNDEZ HUBERNEY'     => 'LONDOÑO HERNÁNDEZ HUBERNEY',
            'LONDÓDIO HERMÁNDEZ HUBERNEY'    => 'LONDOÑO HERNÁNDEZ HUBERNEY',
            // Orden específico para coincidir con `ProfesoresRealSeeder` (first_name HUBERNEY, last_name LONDÓDIO HERMÁNDEZ)
            'LONDÓDIO HERMÁNDEZ HUBERNEY'    => 'HUBERNEY LONDÓDIO HERMÁNDEZ',
            'BERMUDEZ PAREDES ANA CARGUINA'   => 'BERMÚDEZ PAREDES ANA CAROLINA',
            'BERMUDIZ PAREDES ANA CAROLINA'   => 'BERMÚDEZ PAREDES ANA CAROLINA',
            'AIMS GONZALEZ CLAUDIA MARCEJA'   => 'ARIAS GONZALEZ CLAUDIA MARCELA',
            'ARIAS GONZALEZ CLAUDIA MARCEJA'  => 'ARIAS GONZALEZ CLAUDIA MARCELA',
            'GIZMAN LOPEZ ISABEL CRISTINA'    => 'GUZMAN LOPEZ ISABEL CRISTINA',
            'GUZMÁN LOPEZ ISABEL CRISTINA'    => 'GUZMAN LOPEZ ISABEL CRISTINA',
            'REGIONO CARRERA OBESY VIVIANA'   => 'PERDOMO CABRERA DESY VIVIANA',
            'CLAUDIO RAYO JULIÁN DAVID'       => 'CLAVID RAYO JULIÁN DAVID',
            'CLAVIIO RAYO JULIÁN DAVID'       => 'CLAVID RAYO JULIÁN DAVID',
            'VILLAFAIÈ VIDAL MARCO ANTONIO'   => 'VILLAFAÑE VIDAL MARCO ANTONIO',
            'GOYES VALENCIA DIANA YISEI.'     => 'GOYES VALENCIA DIANA YISEI',
            'BAREÑO APONTE ALVARO JAVIER'     => 'BARERO APONTE ALVARO JAVIER',
            'SARINA ROJAS FRANCY JANED'       => 'SARINA ROJAS FRANCY JANETH',
            'LIBREROS LONDONO GIANCARLO'      => 'LIBREROS LONDOÑO GIANCARLO',
            'VALENCIA MONTAÑO JHON EDOARDO'   => 'VALENCIA MONTAÑO JHON EDGARDO',
            'HELAGO GAVIRIA HOOVER'           => 'GAVIRIA ARANA OSCAR HUMBERTO',
            // Bloque software (claros errores tipográficos hacia docentes reales si existen)
            'SERNA IMBACHI TATIANA'           => 'TATIANA SERNA IMBACHI',
            'SERINA IMBACHI TATIANA'          => 'TATIANA SERNA IMBACHI',
            'RIAÑO BARON YAZMIN HELENA'       => 'YAZMIN HELENA RIAÑO BARON',
            'RUÁJO BARON YAZMEN HUEIMA'       => 'YAZMIN HELENA RIAÑO BARON',
            'SINISTERRA ASPRILLA LINO ALEXANDER' => 'LINO ALEXANDER SINISTERRA ASPRILLA',
            'SHIRISTERRA APRILLA LINO ALEXANDER' => 'LINO ALEXANDER SINISTERRA ASPRILLA',
            'MEDINA MARÍN ALEJANDRO'          => 'ALEJANDRO MEDINA MARÍN',
            'METRINA MÁRIN ALLEMBRO'          => 'ALEJANDRO MEDINA MARÍN',
            'VILLEGAS MONDRAGON FRANCISCO JAVIER' => 'FRANCISCO JAVIER VILLEGAS MONDRAGON',
            'VILLEGAS MONORAGON FRANCISCO JAVIER' => 'FRANCISCO JAVIER VILLEGAS MONDRAGON',
            'ANGULO CASTILLO HECTOR LEONARDO' => 'HECTOR LEONARDO ANGULO CASTILLO',
            'ARQUIDO CASTILLO HECTOR LEONARDO' => 'HECTOR LEONARDO ANGULO CASTILLO',
            'PALOMINO SALCEDO ANDREA'         => 'ANDREA PALOMINO SALCEDO',
            'PALOMINO SALCEBO ANDREA'         => 'ANDREA PALOMINO SALCEDO',
            'RAMIREZ RODRIGUEZ FABIAN ALBERTO' => 'FABIAN ALBERTO RAMIREZ RODRIGUEZ',
            'RAUMEE ZGORIGUEZ FABANA ABRETO'  => 'FABIAN ALBERTO RAMIREZ RODRIGUEZ',
            'RODRIGUEZ MOLINA JHON FREDY'     => 'JHON FREDY RODRIGUEZ MOLINA',
            'RODRIGUEZ MOUNTA UNO FREVI'      => 'JHON FREDY RODRIGUEZ MOLINA',
            'GIRALDO SALAZAR ANA MERCEDES'    => 'ANA MERCEDES GIRALDO SALAZAR',
            'GIRALDO SALAZAR ANA MERCIOES'    => 'ANA MERCEDES GIRALDO SALAZAR',
            'GALEANO GARIBELLO JUAN CARLOS'   => 'JUAN CARLOS GALEANO GARIBELLO',
            'GIALEJAKO GARRBELLO JUAN CARLOS' => 'JUAN CARLOS GALEANO GARIBELLO',
            'OSORIO GUSTAVO ADOLFO'           => 'GUSTAVO ADOLFO OSORIO',
            'COKORO GLISTAYO ADOLFO'          => 'GUSTAVO ADOLFO OSORIO',
            'GARZON VALENCIA ANDRÉS'          => 'ANDRÉS GARZON VALENCIA',
            'GAISCON VALENCIA ANDRÉS'         => 'ANDRÉS GARZON VALENCIA',
            'BUITRAGO UMAÑA RICARDO'          => 'RICARDO BUITRAGO UMAÑA',
            'BUTRAGO UMAÑA RICARDO'           => 'RICARDO BUITRAGO UMAÑA',
            'VILLAFAÑE VIDAL MARCO ANTONIO'   => 'MARCO ANTONIO VILLAFAÑE VIDAL',
            'VILLASAJE VIDAL MARCO ANTONIO'   => 'MARCO ANTONIO VILLAFAÑE VIDAL',
            'CALCEDO BALANTA CARLOS HÉCTOR'   => 'CARLOS HÉCTOR CALCEDO BALANTA',
            'CANCEDO BALANTA CARLOS HÉCTOR'   => 'CARLOS HÉCTOR CALCEDO BALANTA',
            'QUINTERO CAPERA DANIEL'          => 'DANIEL QUINTERO CAPERA',
            'GAVIRIA CLAVIJO DANIEL ESTEBAN'  => 'DANIEL ESTEBAN GAVIRIA CLAVIJO',
            'GUYINGA CLAVIO DANIEL ESTEBAN'   => 'DANIEL ESTEBAN GAVIRIA CLAVIJO',
            'TAMAYO HERNANDEZ WILSON'         => 'WILSON TAMAYO HERNANDEZ',
            'TAMAYO HERMANDEZ WILSON'         => 'WILSON TAMAYO HERNANDEZ',
            'QUIZA TOMICH JORGE'              => 'JORGE QUIZA TOMICH',
        ];
        if (isset($map[$n])) $n = $map[$n];

        $parts = preg_split('/\s+/', $n);
        if (!$parts || count($parts) < 2) return [null, null];
        $last = array_pop($parts);
        $first = implode(' ', $parts);
        return [$first, $last];
    }

    private function stripAccents(string $s): string
    {
        $replacements = [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ñ' => 'N',
        ];
        return strtr($s, $replacements);
    }

    private function tokenizeName(string $s): array
    {
        $s = $this->stripAccents($s);
        $s = preg_replace('/[^A-Z\s]/', ' ', strtoupper($s));
        $parts = array_values(array_filter(preg_split('/\s+/', $s)));
        return array_unique($parts);
    }

    private function jaccard(array $a, array $b): float
    {
        $setA = array_unique($a); $setB = array_unique($b);
        $inter = array_intersect($setA, $setB);
        $union = array_unique(array_merge($setA, $setB));
        return empty($union) ? 0.0 : (count($inter) / count($union));
    }

    private function adminEmpresas(): array
    {
        return [
            ['semestre'=>1,'codigo'=>'801002C','jornada'=>'NOC','cupo'=>30,'docente'=>'TORRES GRANJA ALVARO'],
            ['semestre'=>1,'codigo'=>'801003C','jornada'=>'NOC','cupo'=>30,'docente'=>'RODRIGUEZ MOLINA JHON FREDY'],
            ['semestre'=>1,'codigo'=>'801042C','jornada'=>'NOC','cupo'=>30,'docente'=>'CALCEDO CUENCA MARTIN DE JESUS'],
            ['semestre'=>1,'codigo'=>'801043C','jornada'=>'NOC','cupo'=>30,'docente'=>'RUIZ AGUIRRE JENNIFER ANDREA'],
            ['semestre'=>1,'codigo'=>'801044C','jornada'=>'NOC','cupo'=>30,'docente'=>'CASTRO SEGURA JULIÁN ENRIQUE'],
            ['semestre'=>1,'codigo'=>'802004C','jornada'=>'NOC','cupo'=>30,'docente'=>'CARDONA GIRALDO ANA MARIA'],
            ['semestre'=>2,'codigo'=>'204133C','jornada'=>'DIU','cupo'=>18,'docente'=>'TORRES MEZA JHON WALTER'],
            ['semestre'=>2,'codigo'=>'608032C','jornada'=>'DIU','cupo'=>25,'docente'=>'GOYES VALENCIA DIANA YISEI.'],
            ['semestre'=>2,'codigo'=>'801017C','jornada'=>'DIU','cupo'=>25,'docente'=>'RODRIGUEZ MOLINA JHON FREDY'],
            ['semestre'=>2,'codigo'=>'801058C','jornada'=>'DIU','cupo'=>20,'docente'=>'LOZANO GARCÍA ERNESTO'],
            ['semestre'=>2,'codigo'=>'801078C','jornada'=>'DIU','cupo'=>20,'docente'=>'LARMAT GONZALEZ ROBERTO LUCIEN'],
            ['semestre'=>2,'codigo'=>'801079C','jornada'=>'DIU','cupo'=>25,'docente'=>'SAAVEDRA QUINTERO CESAR AUGUSTO'],
            ['semestre'=>2,'codigo'=>'801082C','jornada'=>'DIU','cupo'=>25,'docente'=>'LONDÓDIO HERMÁNDEZ HUBERNEY'],
            ['semestre'=>3,'codigo'=>'204025C','jornada'=>'NOC','cupo'=>30,'docente'=>'BERMUDIZ PAREDES ANA CAROLINA'],
            ['semestre'=>3,'codigo'=>'801058C','jornada'=>'NOC','cupo'=>25,'docente'=>'PIEDRAHITA PEREZ SARA OFIR'],
            ['semestre'=>3,'codigo'=>'801088C','jornada'=>'NOC','cupo'=>20,'docente'=>'BARRERA REBELLON MAURICIO'],
            ['semestre'=>3,'codigo'=>'801089C','jornada'=>'NOC','cupo'=>25,'docente'=>'TORRES GRANJA ALVARO'],
            ['semestre'=>3,'codigo'=>'801091C','jornada'=>'NOC','cupo'=>25,'docente'=>'ZUÑIGA PRADO JUAN CAMILO'],
            ['semestre'=>3,'codigo'=>'801092C','jornada'=>'NOC','cupo'=>25,'docente'=>'DONCEL GUIAVITA JOSE LUIS'],
            ['semestre'=>3,'codigo'=>'802013C','jornada'=>'NOC','cupo'=>30,'docente'=>'MARTINEZ MAYOR OSCAR FERNANDO'],
            ['semestre'=>4,'codigo'=>'204024C','jornada'=>'DIU','cupo'=>20,'docente'=>'TORRES MEZA JHON WALTER'],
            ['semestre'=>4,'codigo'=>'204026C','jornada'=>'DIU','cupo'=>20,'docente'=>'BERMUDIZ PAREDES ANA CAROLINA'],
            ['semestre'=>4,'codigo'=>'801099C','jornada'=>'DIU','cupo'=>20,'docente'=>'PIEDRAHITA PEREZ SARA OFIR'],
            ['semestre'=>4,'codigo'=>'801104C','jornada'=>'DIU','cupo'=>20,'docente'=>'BARRERA REBELLON MAURICIO'],
            ['semestre'=>4,'codigo'=>'801105C','jornada'=>'DIU','cupo'=>20,'docente'=>'LEDESMA JOHN ALEJANDRO'],
            ['semestre'=>4,'codigo'=>'801111C','jornada'=>'DIU','cupo'=>20,'docente'=>'ROLDAN SOTO CESAR'],
            ['semestre'=>4,'codigo'=>'802028C','jornada'=>'DIU','cupo'=>20,'docente'=>'GOMEZ NOVOA YANETH'],
            ['semestre'=>5,'codigo'=>'204027C','jornada'=>'NOC','cupo'=>40,'docente'=>'ACEVEDO SAMABRÍA ISABEL CRISTINA'],
            ['semestre'=>5,'codigo'=>'801095C','jornada'=>'NOC','cupo'=>25,'docente'=>'ROJAS TREJOS CARLOS ALBERTO'],
            ['semestre'=>5,'codigo'=>'801113C','jornada'=>'NOC','cupo'=>25,'docente'=>'LOZANO JIMENEZ JULIO HERNANDO'],
            ['semestre'=>5,'codigo'=>'801114C','jornada'=>'NOC','cupo'=>25,'docente'=>'BARERO APONTE ALVARO JAVIER'],
            ['semestre'=>5,'codigo'=>'801115C','jornada'=>'NOC','cupo'=>25,'docente'=>'ROLDAN SOTO CESAR'],
            ['semestre'=>5,'codigo'=>'801116C','jornada'=>'NOC','cupo'=>25,'docente'=>'HURTADO LOPEZ ALEXANDER'],
            ['semestre'=>5,'codigo'=>'801126C','jornada'=>'NOC','cupo'=>20,'docente'=>'HELAGO GAVIRIA HOOVER'],
            ['semestre'=>6,'codigo'=>'204028C','jornada'=>'DIU','cupo'=>30,'docente'=>'BERMUDEZ PAREDES ANA CARGUINA'],
            ['semestre'=>6,'codigo'=>'801026C','jornada'=>'DIU','cupo'=>20,'docente'=>'PALOMINO SALCEDO ANDREA'],
            ['semestre'=>6,'codigo'=>'801135C','jornada'=>'DIU','cupo'=>20,'docente'=>'LEDESMA JOHN ALEJANDRO'],
            ['semestre'=>6,'codigo'=>'801134C','jornada'=>'DIU','cupo'=>20,'docente'=>'SARINA ROJAS FRANCY JANED'],
            ['semestre'=>6,'codigo'=>'801143C','jornada'=>'DIU','cupo'=>20,'docente'=>'BARERO APONTE ALVARO JAVIER'],
            ['semestre'=>6,'codigo'=>'802051C','jornada'=>'DIU','cupo'=>20,'docente'=>'SALDARRIAGA HECTOR JORGE'],
            ['semestre'=>7,'codigo'=>'204124C','jornada'=>'NOC','cupo'=>25,'docente'=>'SOLUCYO ROJAS REÑAZ ARMANDO'],
            ['semestre'=>7,'codigo'=>'801124C','jornada'=>'NOC','cupo'=>25,'docente'=>'SARINA ROJAS FRANCY JANED'],
            ['semestre'=>7,'codigo'=>'801157C','jornada'=>'NOC','cupo'=>25,'docente'=>'PALOMINO SALCEDO ANDREA'],
            ['semestre'=>7,'codigo'=>'801156C','jornada'=>'NOC','cupo'=>25,'docente'=>'GOMEZ DIAZ MANCY FABIOLA'],
            ['semestre'=>7,'codigo'=>'801158C','jornada'=>'NOC','cupo'=>25,'docente'=>'BUITRAGO SOTO MAURICIO ALEJANDRO'],
            ['semestre'=>7,'codigo'=>'802041C','jornada'=>'NOC','cupo'=>30,'docente'=>'LOZANO JIMENEZ JULIO HERNANDO'],
        ];
    }

    private function contaduria(): array
    {
        return [
            ['semestre'=>1,'codigo'=>'404002C','jornada'=>'NOC','cupo'=>20,'docente'=>'GARCÍA GUZMÁN CARLOS ALBERTO'],
            ['semestre'=>1,'codigo'=>'801003C','jornada'=>'NOC','cupo'=>20,'docente'=>'GUZMÁN LOPEZ ISABEL CRISTINA'],
            ['semestre'=>1,'codigo'=>'801044C','jornada'=>'NOC','cupo'=>20,'docente'=>'CALCEDO CUENCIA MARTIN DE JESUS'],
            ['semestre'=>1,'codigo'=>'802004C','jornada'=>'NOC','cupo'=>20,'docente'=>'CARMONA BETANCOURT JULIAN ORLANDO'],
            ['semestre'=>1,'codigo'=>'802006C','jornada'=>'NOC','cupo'=>20,'docente'=>'DUQUE CARVAJAL NELSY'],
            ['semestre'=>1,'codigo'=>'802008C','jornada'=>'NOC','cupo'=>20,'docente'=>'RODRIGUEZ JIMENEZ DIEGO FERNANDO'],
            ['semestre'=>2,'codigo'=>'204133C','jornada'=>'NOC','cupo'=>25,'docente'=>'SILVA CABREBA CLAUDIA LILIANA'],
            ['semestre'=>2,'codigo'=>'801002C','jornada'=>'NOC','cupo'=>30,'docente'=>'ESCOBAR NAVIA WILSON'],
            ['semestre'=>2,'codigo'=>'801017C','jornada'=>'NOC','cupo'=>30,'docente'=>'CALCEDO BALANTA CARLOS HÉCTOR'],
            ['semestre'=>2,'codigo'=>'801073C','jornada'=>'NOC','cupo'=>30,'docente'=>'VILLAFAÑE VIDAL MARCO ANTONIO'],
            ['semestre'=>2,'codigo'=>'802013C','jornada'=>'NOC','cupo'=>30,'docente'=>'MARTINEZ MAYOR OSCAR FERNANDO'],
            ['semestre'=>2,'codigo'=>'802014C','jornada'=>'NOC','cupo'=>30,'docente'=>'CLAVID RAYO JULIÁN DAVID'],
            ['semestre'=>3,'codigo'=>'204025C','jornada'=>'DIU','cupo'=>20,'docente'=>'CASTRILLON LLANOS NOHRA LUCIA'],
            ['semestre'=>3,'codigo'=>'801048C','jornada'=>'DIU','cupo'=>20,'docente'=>'BAREÑO APONTE ALVARO JAVIER'],
            ['semestre'=>3,'codigo'=>'801069C','jornada'=>'DIU','cupo'=>20,'docente'=>'ESCOBAR NAVIA WILSON'],
            ['semestre'=>3,'codigo'=>'801074C','jornada'=>'DIU','cupo'=>20,'docente'=>'CALCEDO CUENCIA MARTIN DE JESUS'],
            ['semestre'=>3,'codigo'=>'801083C','jornada'=>'DIU','cupo'=>20,'docente'=>'CALCEDO BALANTA CARLOS HÉCTOR'],
            ['semestre'=>3,'codigo'=>'801099C','jornada'=>'DIU','cupo'=>20,'docente'=>'VILLAFAÑE VIDAL MARCO ANTONIO'],
            ['semestre'=>3,'codigo'=>'802015C','jornada'=>'DIU','cupo'=>20,'docente'=>'CLAVID RAYO JULIÁN DAVID'],
            ['semestre'=>3,'codigo'=>'802016C','jornada'=>'NOC','cupo'=>20,'docente'=>'CASTRILLON LLANOS NOHRA LUCIA'],
            ['semestre'=>5,'codigo'=>'204027C','jornada'=>'NOC','cupo'=>20,'docente'=>'DONCEL GUIAVITA JOSE LUIS'],
            ['semestre'=>5,'codigo'=>'801078C','jornada'=>'NOC','cupo'=>20,'docente'=>'MORALES TULIO HERMANDO'],
            ['semestre'=>5,'codigo'=>'801126C','jornada'=>'NOC','cupo'=>20,'docente'=>'MORENO MINA LUBIAN HERNANDO'],
            ['semestre'=>5,'codigo'=>'802007C','jornada'=>'NOC','cupo'=>20,'docente'=>'DUQUE CARVAJAL NELSY'],
            ['semestre'=>5,'codigo'=>'802026C','jornada'=>'NOC','cupo'=>20,'docente'=>'CARMONA BETANCOURT JULIAN ORLANDO'],
            ['semestre'=>5,'codigo'=>'802029C','jornada'=>'NOC','cupo'=>20,'docente'=>'CARMONA BETANCOURT JULIAN ORLANDO'],
            ['semestre'=>5,'codigo'=>'802032C','jornada'=>'NOC','cupo'=>20,'docente'=>'CARDONA GIRALDO ANA MARIA'],
            ['semestre'=>6,'codigo'=>'204028C','jornada'=>'DIU','cupo'=>20,'docente'=>'CASTRILLON LLANOS NOHRA LUCIA'],
            ['semestre'=>6,'codigo'=>'802039C','jornada'=>'DIU','cupo'=>20,'docente'=>'CARDONA GIRALDO ANA MARIA'],
            ['semestre'=>6,'codigo'=>'802040C','jornada'=>'DIU','cupo'=>20,'docente'=>'CARMONA BETANCOURT JULIAN ORLANDO'],
            ['semestre'=>6,'codigo'=>'802042C','jornada'=>'DIU','cupo'=>20,'docente'=>'RODRIGUEZ JIMENEZ DIEGO FERNANDO'],
            ['semestre'=>6,'codigo'=>'802047C','jornada'=>'DIU','cupo'=>20,'docente'=>'LASSO CUTIVA ISABELINO'],
            ['semestre'=>6,'codigo'=>'802049C','jornada'=>'DIU','cupo'=>20,'docente'=>'LOZANO JIMENEZ JULIO HERNANDO'],
            ['semestre'=>6,'codigo'=>'802056C','jornada'=>'DIU','cupo'=>20,'docente'=>'DUQUE CARVAJAL NELSY'],
            ['semestre'=>8,'codigo'=>'801195C','jornada'=>'NOC','cupo'=>30,'docente'=>'RODRIGUEZ JIMENEZ DIEGO FERNANDO'],
            ['semestre'=>8,'codigo'=>'802041C','jornada'=>'NOC','cupo'=>30,'docente'=>'LOZANO JIMENEZ JULIO HERNANDO'],
            ['semestre'=>8,'codigo'=>'802060C','jornada'=>'NOC','cupo'=>30,'docente'=>'PAZMIÑO LUNA VICTOR ANDRÉS'],
            ['semestre'=>8,'codigo'=>'802061C','jornada'=>'NOC','cupo'=>30,'docente'=>'CARMONA BETANCOURT JULIAN ORLANDO'],
            ['semestre'=>8,'codigo'=>'802086C','jornada'=>'NOC','cupo'=>30,'docente'=>'LASSO CUTIVA ISABELINO'],
            ['semestre'=>8,'codigo'=>'802089C','jornada'=>'NOC','cupo'=>30,'docente'=>'MORENO MINA LUBIAN HERNANDO'],
        ];
    }

    private function comercioExterior(): array
    {
        return [
            ['semestre'=>1,'codigo'=>'801002C','jornada'=>'DIU','cupo'=>30,'docente'=>'LARGO CAÑAVERAL EDWIN'],
            ['semestre'=>1,'codigo'=>'801003C','jornada'=>'DIU','cupo'=>20,'docente'=>'CUARTAS PEREZ CARLOS ANDRES'],
            ['semestre'=>1,'codigo'=>'80104AC','jornada'=>'DIU','cupo'=>30,'docente'=>'VILLAFAIÈ VIDAL MARCO ANTONIO'],
            ['semestre'=>1,'codigo'=>'80104BC','jornada'=>'DIU','cupo'=>30,'docente'=>'MORALES TULIO HERMANDO'],
            ['semestre'=>1,'codigo'=>'80104CC','jornada'=>'DIU','cupo'=>40,'docente'=>'BUITRAGO UMAÑA RICARDO'],
            ['semestre'=>1,'codigo'=>'802004C','jornada'=>'DIU','cupo'=>30,'docente'=>'CLAVIIO RAYO JULIÁN DAVID'],
            ['semestre'=>2,'codigo'=>'204025C','jornada'=>'DIU','cupo'=>35,'docente'=>'ANGULO CASTILLO HECTOR LEONARDO'],
            ['semestre'=>2,'codigo'=>'204135C','jornada'=>'DIU','cupo'=>55,'docente'=>'AIMS GONZALEZ CLAUDIA MARCEJA'],
            ['semestre'=>2,'codigo'=>'801017C','jornada'=>'DIU','cupo'=>30,'docente'=>'LOPEZ REYES ANGELA MARIA'],
            ['semestre'=>2,'codigo'=>'801026C','jornada'=>'DIU','cupo'=>20,'docente'=>'GOMEZ VALENCIA WILLIAM'],
            ['semestre'=>2,'codigo'=>'801058C','jornada'=>'DIU','cupo'=>25,'docente'=>'VILLAFAIÈ VIDAL MARCO ANTONIO'],
            ['semestre'=>2,'codigo'=>'801059C','jornada'=>'DIU','cupo'=>20,'docente'=>'LOZANO GARCÍA ERNESTO'],
            ['semestre'=>2,'codigo'=>'801071C','jornada'=>'DIU','cupo'=>35,'docente'=>'MORALES TULIO HERMANDO'],
            ['semestre'=>3,'codigo'=>'204026C','jornada'=>'DIU','cupo'=>35,'docente'=>'ALVAREZ CORTES HAROLD'],
            ['semestre'=>3,'codigo'=>'404032C','jornada'=>'DIU','cupo'=>35,'docente'=>'GARCÍA GUZMÁN CARLOS ALBERTO'],
            ['semestre'=>3,'codigo'=>'801038C','jornada'=>'DIU','cupo'=>20,'docente'=>'GUZMAN LOPEZ ISABEL CRISTINA'],
            ['semestre'=>3,'codigo'=>'801088C','jornada'=>'DIU','cupo'=>25,'docente'=>'REGIONO CARRERA OBESY VIVIANA'],
            ['semestre'=>3,'codigo'=>'801090C','jornada'=>'DIU','cupo'=>20,'docente'=>'CALCEO CLUNERA MARTIN DE JESUS'],
            ['semestre'=>3,'codigo'=>'801100C','jornada'=>'DIU','cupo'=>35,'docente'=>'GAVIRIA ARANA OSCAR HUMBERTO'],
            ['semestre'=>3,'codigo'=>'802013C','jornada'=>'DIU','cupo'=>30,'docente'=>'MONCADA RENDON JUAN CARLOS'],
            ['semestre'=>4,'codigo'=>'204027C','jornada'=>'DIU','cupo'=>40,'docente'=>'ALVAREZ CORTES HAROLD'],
            ['semestre'=>4,'codigo'=>'402051C','jornada'=>'DIU','cupo'=>40,'docente'=>'SUVA CARRERA CLAUDIA LILIANA'],
            ['semestre'=>4,'codigo'=>'801078C','jornada'=>'DIU','cupo'=>20,'docente'=>'LARMAT GONZALEZ ROBERTO LUCIEN'],
            ['semestre'=>4,'codigo'=>'801102C','jornada'=>'DIU','cupo'=>40,'docente'=>'MORALES TULIO HERMANDO'],
            ['semestre'=>4,'codigo'=>'801103C','jornada'=>'DIU','cupo'=>40,'docente'=>'GAVIRIA ARANA OSCAR HUMBERTO'],
            ['semestre'=>4,'codigo'=>'801104C','jornada'=>'DIU','cupo'=>20,'docente'=>'LIBREROS LONDONO GIANCARLO'],
            ['semestre'=>4,'codigo'=>'801110C','jornada'=>'DIU','cupo'=>40,'docente'=>'CASTELLANOS GARZÓN NIDIA STEFANIA'],
            ['semestre'=>5,'codigo'=>'204028C','jornada'=>'DIU','cupo'=>30,'docente'=>'ALVAREZ CORTES HAROLD'],
            ['semestre'=>5,'codigo'=>'801095C','jornada'=>'DIU','cupo'=>25,'docente'=>'BUITRAGO SOTO MAURICIO ALEJANDRO'],
            ['semestre'=>5,'codigo'=>'801111C','jornada'=>'DIU','cupo'=>28,'docente'=>'MORALES TULIO HERMANDO'],
            ['semestre'=>5,'codigo'=>'801121C','jornada'=>'DIU','cupo'=>38,'docente'=>'GOMEZ ZULUAGA CLAUDIA MILENA'],
            ['semestre'=>5,'codigo'=>'801122C','jornada'=>'DIU','cupo'=>38,'docente'=>'SALDARRIAGA HECTOR JORGE'],
            ['semestre'=>5,'codigo'=>'801125C','jornada'=>'DIU','cupo'=>38,'docente'=>'LEMIR AGUIRRE SARA MARÍA'],
            ['semestre'=>5,'codigo'=>'801126C','jornada'=>'DIU','cupo'=>20,'docente'=>'GOYES VALENCIA DIANA YISEI'],
            ['semestre'=>6,'codigo'=>'801135C','jornada'=>'DIU','cupo'=>12,'docente'=>'PERDOMO CABRERA DESY VIVIANA'],
            ['semestre'=>6,'codigo'=>'801137C','jornada'=>'DIU','cupo'=>12,'docente'=>'GOMEZ ZULUAGA CLAUDIA MILENA'],
            ['semestre'=>6,'codigo'=>'801138C','jornada'=>'DIU','cupo'=>12,'docente'=>'LEMIR AGUIRRE SARA MARÍA'],
            ['semestre'=>6,'codigo'=>'801139C','jornada'=>'DIU','cupo'=>12,'docente'=>'LARGO CAÑAVERAL EDWIN'],
            ['semestre'=>6,'codigo'=>'801140C','jornada'=>'DIU','cupo'=>12,'docente'=>'VALENCIA MONTAÑO JHON EDOARDO'],
            ['semestre'=>6,'codigo'=>'802031C','jornada'=>'DIU','cupo'=>32,'docente'=>'CLAUDIO RAYO JULIÁN DAVID'],
            ['semestre'=>7,'codigo'=>'208013C','jornada'=>'DIU','cupo'=>35,'docente'=>'ARIAS GONZALEZ CLAUDIA MARCELA'],
            ['semestre'=>7,'codigo'=>'801154C','jornada'=>'DIU','cupo'=>40,'docente'=>'MORALES TULIO HERMANDO'],
            ['semestre'=>7,'codigo'=>'801156C','jornada'=>'DIU','cupo'=>25,'docente'=>'GOMEZ ZULUAGA CLAUDIA MILENA'],
            ['semestre'=>7,'codigo'=>'801157C','jornada'=>'DIU','cupo'=>25,'docente'=>'BUITRAGO UMAÑA RICARDO'],
            ['semestre'=>7,'codigo'=>'801158C','jornada'=>'DIU','cupo'=>35,'docente'=>'BUITRAGO UMAÑA RICARDO'],
            ['semestre'=>7,'codigo'=>'801159C','jornada'=>'DIU','cupo'=>35,'docente'=>'CASTELLANOS GARZÓN NIDIA STEFANIA'],
            ['semestre'=>7,'codigo'=>'802051C','jornada'=>'DIU','cupo'=>20,'docente'=>'VALENCIA MONTAÑO JHON EDOARDO'],
            ['semestre'=>8,'codigo'=>'204163C','jornada'=>'DIU','cupo'=>30,'docente'=>'TORRES MEZA JHON WALTER'],
            ['semestre'=>8,'codigo'=>'801136C','jornada'=>'DIU','cupo'=>35,'docente'=>'LEMIR AGUIRRE SARA MARÍA'],
            ['semestre'=>8,'codigo'=>'801192C','jornada'=>'DIU','cupo'=>35,'docente'=>'LARGO CAÑAVERAL EDWIN'],
            ['semestre'=>8,'codigo'=>'801194C','jornada'=>'DIU','cupo'=>35,'docente'=>'LONDONO HERMÁNDEZ HUBERNEY'],
            ['semestre'=>8,'codigo'=>'802041C','jornada'=>'DIU','cupo'=>30,'docente'=>'VALENCIA MONTAÑO JHON EDOARDO'],
            ['semestre'=>8,'codigo'=>'802078C','jornada'=>'DIU','cupo'=>35,'docente'=>'HERMÁNDEZ PALACIO MARIANA CAROLINA'],
        ];
    }

    private function software(): array
    {
        return [
            // Semestre 1
            ['semestre'=>1,'codigo'=>'11103XC','jornada'=>'DUI','cupo'=>30,'docente'=>'GIRALDO SALAZAR ESTEBAN ELIAS'],
            ['semestre'=>1,'codigo'=>'40400XC','jornada'=>'DUI','cupo'=>30,'docente'=>'VILLEGAS MONDRAGON FRANCISCO JAVIER'],
            ['semestre'=>1,'codigo'=>'70100XC','jornada'=>'DUI','cupo'=>30,'docente'=>'MEDINA MARÍN ALEJANDRO'],
            ['semestre'=>1,'codigo'=>'70102XC','jornada'=>'DUI','cupo'=>30,'docente'=>'SERNA IMBACHI TATIANA'],
            ['semestre'=>1,'codigo'=>'70200XC','jornada'=>'DUI','cupo'=>30,'docente'=>'RIAÑO BARON YAZMIN HELENA'],
            ['semestre'=>1,'codigo'=>'70001XC','jornada'=>'DUI','cupo'=>30,'docente'=>'SINISTERRA ASPRILLA LINO ALEXANDER'],
            // Semestre 2
            ['semestre'=>2,'codigo'=>'11104XC','jornada'=>'NOC','cupo'=>25,'docente'=>'TAMAYO HERNANDEZ WILSON'],
            ['semestre'=>2,'codigo'=>'11110XC','jornada'=>'NOC','cupo'=>40,'docente'=>'QUIZA TOMICH JORGE'],
            ['semestre'=>2,'codigo'=>'40402XC','jornada'=>'NOC','cupo'=>30,'docente'=>'VILLEGAS MONDRAGON FRANCISCO JAVIER'],
            ['semestre'=>2,'codigo'=>'70203XC','jornada'=>'NOC','cupo'=>40,'docente'=>'CALCEDO BALANTA CARLOS HÉCTOR'],
            ['semestre'=>2,'codigo'=>'70301XC','jornada'=>'NOC','cupo'=>40,'docente'=>'QUINTERO CAPERA DANIEL'],
            // Semestre 3
            ['semestre'=>3,'codigo'=>'70403XC','jornada'=>'DUI','cupo'=>30,'docente'=>'ANGULO CASTILLO HECTOR LEONARDO'],
            ['semestre'=>3,'codigo'=>'70204XC','jornada'=>'DUI','cupo'=>30,'docente'=>'RIAÑO BARON YAZMIN HELENA'],
            ['semestre'=>3,'codigo'=>'70205XC','jornada'=>'DUI','cupo'=>30,'docente'=>'CALCEDO BALANTA CARLOS HÉCTOR'],
            ['semestre'=>3,'codigo'=>'70300XC','jornada'=>'DUI','cupo'=>30,'docente'=>'QUINTERO CAPERA DANIEL'],
            ['semestre'=>3,'codigo'=>'70302XC','jornada'=>'DUI','cupo'=>40,'docente'=>'GAVIRIA CLAVIJO DANIEL ESTEBAN'],
            // Semestre 4
            ['semestre'=>4,'codigo'=>'70404XC','jornada'=>'NOC','cupo'=>30,'docente'=>'ANGULO CASTILLO HECTOR LEONARDO'],
            ['semestre'=>4,'codigo'=>'70301XC','jornada'=>'NOC','cupo'=>30,'docente'=>'PALOMINO SALCEDO ANDREA'],
            ['semestre'=>4,'codigo'=>'70400XC','jornada'=>'NOC','cupo'=>30,'docente'=>'QUINTERO CAPERA DANIEL'],
            ['semestre'=>4,'codigo'=>'70500XC','jornada'=>'NOC','cupo'=>30,'docente'=>'RAMIREZ RODRIGUEZ FABIAN ALBERTO'],
            ['semestre'=>4,'codigo'=>'70101XC','jornada'=>'NOC','cupo'=>30,'docente'=>'RODRIGUEZ MOLINA JHON FREDY'],
            // Semestre 5
            ['semestre'=>5,'codigo'=>'70501XC','jornada'=>'DUI','cupo'=>20,'docente'=>'GIRALDO SALAZAR ANA MERCEDES'],
            ['semestre'=>5,'codigo'=>'70502XC','jornada'=>'DUI','cupo'=>15,'docente'=>'GALEANO GARIBELLO JUAN CARLOS'],
            ['semestre'=>5,'codigo'=>'70503XC','jornada'=>'DUI','cupo'=>20,'docente'=>'OSORIO GUSTAVO ADOLFO'],
            ['semestre'=>5,'codigo'=>'70504XC','jornada'=>'DUI','cupo'=>20,'docente'=>'RAMIREZ RODRIGUEZ FABIAN ALBERTO'],
            ['semestre'=>5,'codigo'=>'70505XC','jornada'=>'DUI','cupo'=>20,'docente'=>'GARZON VALENCIA ANDRÉS'],
            // Semestre 6
            ['semestre'=>6,'codigo'=>'70506XC','jornada'=>'NOC','cupo'=>25,'docente'=>'VILLAFAÑE VIDAL MARCO ANTONIO'],
            ['semestre'=>6,'codigo'=>'70507XC','jornada'=>'NOC','cupo'=>20,'docente'=>'GIRALDO SALAZAR ANA MERCEDES'],
            ['semestre'=>6,'codigo'=>'70508XC','jornada'=>'NOC','cupo'=>15,'docente'=>'GALEANO GARIBELLO JUAN CARLOS'],
            ['semestre'=>6,'codigo'=>'70509XC','jornada'=>'NOC','cupo'=>20,'docente'=>'OSORIO GUSTAVO ADOLFO'],
            ['semestre'=>6,'codigo'=>'70510XC','jornada'=>'NOC','cupo'=>25,'docente'=>'GARZON VALENCIA ANDRÉS'],
            // Semestre 7
            ['semestre'=>7,'codigo'=>'70511XC','jornada'=>'DUI','cupo'=>12,'docente'=>'SERNA IMBACHI TATIANA'],
            ['semestre'=>7,'codigo'=>'70512XM','jornada'=>'DUI','cupo'=>30,'docente'=>'PALOMINO SALCEDO ANDREA'],
            ['semestre'=>7,'codigo'=>'70513XM','jornada'=>'DUI','cupo'=>25,'docente'=>'BUITRAGO UMAÑA RICARDO'],
        ];
    }
}
