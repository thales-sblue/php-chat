<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('clients')->onDelete('cascade');
            $table->text('last_message_content')->nullable();
            $table->timestamp('last_message_time')->nullable();
            $table->integer('unread_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('conversations');
    }
};
