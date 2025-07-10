<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('clients')->onDelete('cascade');
            $table->text('content');
            $table->enum('priority', ['normal', 'urgent'])->default('normal');
            $table->enum('status', ['queued', 'processing', 'sent', 'failed'])->default('queued');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('messages');
    }
};
