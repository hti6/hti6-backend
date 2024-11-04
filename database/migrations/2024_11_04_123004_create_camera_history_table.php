<?php

use App\Helpers\Constants;
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
        Schema::create('camera_history', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->timestamps();
            $table->enum('priority', Constants::PRIORITIES)->default(Constants::DEFAULT_PROPERTY);
            $table->foreignUlid('camera_id')->constrained('cameras')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camera_history');
    }
};
