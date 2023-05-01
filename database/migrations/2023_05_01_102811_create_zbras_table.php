<?php

use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zbras', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Message::class, 'message_id');
            $table->string('text');
            $table->string('image_url');
            $table->string('image_height');
            $table->string('image_width');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zbras');
    }
};
