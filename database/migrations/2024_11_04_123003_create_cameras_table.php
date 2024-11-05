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
        Schema::create('cameras', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->timestamps();
            $table->magellanPoint('point');
            $table->string('name');
            $table->string('url');
            $table->string('port')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};
