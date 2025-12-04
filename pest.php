<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

uses(
    Tests\TestCase::class
)->in('Unit');

expect()->extend('toBeOne', fn () => $this->toBe(1));
