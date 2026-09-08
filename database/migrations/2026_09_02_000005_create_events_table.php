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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('terms_and_policy')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location');
            $table->string('location_type')->default('physical');
            $table->text('arrival_instructions')->nullable();
            $table->string('image')->nullable();
            $table->integer('max_participants')->nullable(); // max capacity limit
            $table->string('registration_type')->default('admin_approval');
            $table->dateTime('registration_deadline')->nullable();
            $table->json('registration_fields')->nullable();
            $table->boolean('survey_enabled')->default(false);
            $table->json('survey_questions')->nullable();
            $table->boolean('survey_sent')->default(false);
            $table->foreignId('committee_id')->nullable()->constrained('committees')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
