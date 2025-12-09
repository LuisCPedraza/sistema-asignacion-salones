<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$user = \App\Models\User::find(3);
if ($user) {
    echo "User ID 3: " . $user->name . "\n";
    echo "Role: " . ($user->role ? $user->role->slug : "NO ROLE") . "\n";
    echo "Has Teacher: " . ($user->teacher ? "YES (Teacher ID: " . $user->teacher->id . ")" : "NO - THIS IS THE PROBLEM!") . "\n";
} else {
    echo "User ID 3 NOT FOUND\n";
}
