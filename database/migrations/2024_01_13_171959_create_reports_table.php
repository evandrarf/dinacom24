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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('social_assistance_id')->constrained('social_assistances')->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime('taken_at')->nullable()->comment('waktu pengambilan bantuan sosial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
