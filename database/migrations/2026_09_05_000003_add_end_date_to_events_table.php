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
            $table->dateTime('end_date')->nullable()->after('event_date');
        });

        // Copy existing event_date into end_date so there are no nulls for old events
        \DB::table('events')->whereNull('end_date')->update([
            'end_date' => \DB::raw('event_date')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
};
