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
            $table->boolean('allow_group_registration')->default(false)->after('registration_type');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->date('birthday')->nullable()->after('gender');
            $table->string('group_code')->nullable()->index()->after('status');
            $table->boolean('is_group_primary')->default(false)->after('group_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('allow_group_registration');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['birthday', 'group_code', 'is_group_primary']);
        });
    }
};
