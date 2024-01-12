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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('type');
            $table->unsignedBigInteger('data_id');
            $table->string('data_type');
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('social_assistance_recipient_id')->constrained('social_assistance_recipients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('social_assistance_id')->constrained('social_assistances')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
