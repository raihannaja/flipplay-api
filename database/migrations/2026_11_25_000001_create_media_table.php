<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('type', 10);
            $table->string('genre', 100);
            $table->unsignedSmallInteger('year');
            $table->decimal('rating', 3, 1);
            $table->unsignedSmallInteger('episodes')->nullable();
            $table->unsignedSmallInteger('duration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
