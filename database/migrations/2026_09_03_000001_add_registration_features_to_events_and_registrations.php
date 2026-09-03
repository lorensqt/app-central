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
            $table->string('registration_type')->default('admin_approval')->after('max_participants');
            $table->dateTime('registration_deadline')->nullable()->after('registration_type');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->boolean('attended')->default(false)->after('status');
            $table->dateTime('attended_at')->nullable()->after('attended');
            $table->string('ticket_code', 8)->nullable()->after('attended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['registration_type', 'registration_deadline']);
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['attended', 'attended_at', 'ticket_code']);
        });
    }
};
