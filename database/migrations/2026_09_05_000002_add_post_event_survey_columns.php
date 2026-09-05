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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('survey_enabled')->default(false)->after('registration_fields');
            $table->json('survey_questions')->nullable()->after('survey_enabled');
            $table->boolean('survey_sent')->default(false)->after('survey_questions');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->json('survey_responses')->nullable()->after('custom_fields');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['survey_enabled', 'survey_questions', 'survey_sent']);
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('survey_responses');
        });
    }
};
