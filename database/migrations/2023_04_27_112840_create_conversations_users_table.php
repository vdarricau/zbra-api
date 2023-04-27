<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Conversation::class, 'conversation_id')->constrained();
            $table->foreignIdFor(User::class, 'user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations_users');
    }
};
