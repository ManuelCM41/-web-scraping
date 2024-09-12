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
        Schema::create('article_details', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('extracto')->nullable();
            $table->string('avatar')->nullable();
            $table->string('categoria');
            $table->string('imagen', 2048)->nullable();
            $table->string('autor')->nullable();
            $table->string('fecha')->nullable();
            $table->text('p1')->nullable();
            $table->text('p2')->nullable();
            $table->text('p3')->nullable();
            $table->text('p4')->nullable();
            $table->text('p5')->nullable();
            $table->text('p6')->nullable();
            $table->text('p7')->nullable();
            $table->text('p8')->nullable();
            $table->text('p9')->nullable();
            $table->text('p10')->nullable();
            $table->text('p11')->nullable();
            $table->text('p12')->nullable();
            $table->text('p13')->nullable();
            $table->text('p14')->nullable();
            $table->text('p15')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_details');
    }
};
