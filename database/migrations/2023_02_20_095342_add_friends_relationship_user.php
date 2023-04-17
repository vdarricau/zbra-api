<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'friend_connecting_id');
            $table->foreignIdFor(User::class, 'friend_accepting_connection_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
