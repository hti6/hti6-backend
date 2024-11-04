<?php

use App\Helpers\Constants;
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
        Schema::create('damage_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->magellanPoint('point');
            $table->enum('priority', Constants::PRIORITIES)->default(Constants::DEFAULT_PROPERTY);
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_requests');
    }
};
