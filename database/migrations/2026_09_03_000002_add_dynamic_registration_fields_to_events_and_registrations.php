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
            $table->json('registration_fields')->nullable()->after('registration_deadline');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->json('custom_fields')->nullable()->after('ticket_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('registration_fields');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('custom_fields');
        });
    }
};
