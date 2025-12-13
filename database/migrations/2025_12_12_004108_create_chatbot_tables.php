<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de conversaciones
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->text('context')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('last_activity');
        });

        // Tabla de mensajes
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                  ->constrained('chat_conversations')
                  ->onDelete('cascade');
            $table->enum('sender', ['user', 'bot']);
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('conversation_id');
            $table->index(['conversation_id', 'created_at']);
        });

        // Tabla de base de conocimiento
        Schema::create('chat_knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100)->index();
            $table->text('question');
            $table->text('answer');
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            
            $table->index('usage_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('chat_knowledge_base');
    }
};
