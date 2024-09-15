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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->mediumText('url')->nullable();
            $table->string('urlPrincipal')->nullable();
            $table->mediumText('path')->nullable();
            $table->string('extracto')->nullable();
            $table->string('avatar')->nullable();
            $table->string('categoria')->nullable();
            $table->string('imagen', 2048)->nullable();
            $table->string('autor')->nullable();
            $table->string('fecha')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
