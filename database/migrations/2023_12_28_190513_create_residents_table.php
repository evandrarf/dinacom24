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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('family_card_number')->unique();
            $table->string('head_of_family_nik')->unique();
            $table->string('head_of_family_name');
            $table->string('address');
            $table->string('rt');
            $table->string('rw');
            $table->foreignId('village_id')->constrained('villages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('district');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('phone_number')->nullable();
            $table->bigInteger('monthly_income')->nullable();
            $table->integer('dependent_count')->nullable();
            $table->foreignId('family_card_file_id')->nullable()->constrained('files');
            $table->foreignId('identity_card_file_id')->nullable()->constrained('files');
            $table->enum('house_ownership', ['owned', 'rented', 'join'])->default('owned');
            $table->enum('house_type', ['permanent', 'semi_permanent'])->default('permanent');
            $table->string('password')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
