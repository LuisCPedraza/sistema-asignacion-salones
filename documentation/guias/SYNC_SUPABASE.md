# Sincronización de Datos a Supabase

## Uso

Para sincronizar todos los datos locales (SQLite) con Supabase (PostgreSQL), ejecuta:

```bash
php artisan sync:supabase
```

## ¿Qué hace este comando?

1. Conecta con tu base de datos SQLite local (donde están todos los datos de prueba)
2. Se conecta a Supabase usando las credenciales del `.env`
3. Para cada tabla:
   - Obtiene todos los registros de SQLite
   - Limpia la tabla en Supabase (TRUNCATE)
   - Inserta los datos en lotes de 100 registros

## Orden de sincronización

Las tablas se sincronizan en este orden para respetar las dependencias:

1. `roles`
2. `users`
3. `academic_periods`
4. `careers`
5. `semesters`
6. `student_groups`
7. `teachers`
8. `subjects`
9. `time_slots`
10. `buildings`
11. `classrooms`
12. `classroom_availabilities`
13. `teacher_availabilities`
14. `assignment_rules`
15. `assignments`
16. `course_schedules`

## Configuración requerida en .env

Asegúrate de tener en tu `.env`:

```
DB_HOST=aws-1-us-east-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE_PROD=postgres
DB_USERNAME_PROD=postgres.vzgdbeycqebftjnsmcpj
DB_PASSWORD_PROD=0hIdb3JDvTdwHoF5
```

## Ejemplo de salida

```
Iniciando sincronizacion de datos a Supabase...
Conexion a Supabase establecida
[SKIP] roles: sin datos
[OK] users: 5 registros sincronizados
[OK] academic_periods: 2 registros sincronizados
[OK] careers: 3 registros sincronizados
[OK] semesters: 6 registros sincronizados
[OK] student_groups: 48 registros sincronizados
...
Sincronizacion completada con exito!
```

## Próximas migraciones en Render

Una vez que el código esté en Render, el sistema:

1. Detectará automáticamente que `APP_ENV=production`
2. Usará Supabase como base de datos principal
3. Los datos ya estarán disponibles desde el primer despliegue

## Notas importantes

- Este comando **trunca (limpia)** las tablas en Supabase antes de insertar
- Si hay datos valiosos en Supabase, considera hacer un backup primero
- El comando es seguro y puede correrse múltiples veces sin problemas
- Si una tabla falla, el comando continúa con las otras (sin parar)
