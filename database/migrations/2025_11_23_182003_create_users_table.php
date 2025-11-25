<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(false);
            $table->boolean('temporary_access')->default(false); // Asegúrate de que esta línea existe
            $table->timestamp('access_expires_at')->nullable(); // Y esta también
            $table->timestamp('temporary_access_expires_at')->nullable(); // Parece que también necesitas esta
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
