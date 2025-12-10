<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler para notificaciones de expiración de profesores invitados
Schedule::command('guest-teachers:send-expiration-notifications --days=7')
    ->dailyAt('09:00')
    ->description('Enviar notificaciones a profesores invitados próximos a expirar')
    ->withoutOverlapping();
