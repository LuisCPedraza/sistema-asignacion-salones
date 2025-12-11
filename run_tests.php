<?php

// Cargar Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Cargar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Ejecutar tests
$exitCode = 0;
echo shell_exec('cd ' . escapeshellarg(__DIR__) . ' && ./vendor/bin/pest 2>&1');

exit($exitCode);
?>
