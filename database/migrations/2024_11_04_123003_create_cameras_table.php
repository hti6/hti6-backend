<?php

use Illuminate\Database\Migrations\Migration;
use Clickbar\Magellan\Schema\MagellanBlueprint as Blueprint;
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
            $table->string('resolution');
            $table->string('name');
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
