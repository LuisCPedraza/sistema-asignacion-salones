#!/bin/bash

# Script para exportar datos de SQLite a archivos SQL
# y luego importarlos en Supabase

DB_FILE="database/database.sqlite"

echo "Exportando datos de SQLite..."

# Crear carpeta para backups
mkdir -p storage/backups

# Exportar esquema y datos
sqlite3 "$DB_FILE" ".dump" > storage/backups/sqlite_dump.sql

echo "Datos exportados a: storage/backups/sqlite_dump.sql"
echo ""
echo "Ahora debes:"
echo "1. Copiar el contenido de storage/backups/sqlite_dump.sql"
echo "2. Ir a Supabase Dashboard -> SQL Editor"
echo "3. Pegar y ejecutar el contenido"
echo ""
echo "O usa este comando para importar directamente si tienes psql instalado:"
echo "psql -h aws-1-us-east-2.pooler.supabase.com -U postgres.vzgdbeycqebftjnsmcpj -d postgres -f storage/backups/sqlite_dump.sql"
