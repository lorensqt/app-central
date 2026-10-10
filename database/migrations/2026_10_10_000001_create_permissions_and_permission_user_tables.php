<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('group')->default('Committees');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'permission_id']);
        });

        // Seed default app access permissions
        DB::table('permissions')->insert([
            [
                'name' => 'Event Management',
                'slug' => 'events.access',
                'group' => 'Committees',
                'description' => 'Coordinate assemblies, schedule corporate gatherings, and approve registration RSVPs.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Election Management',
                'slug' => 'elections.access',
                'group' => 'Committees',
                'description' => 'Establish secure ballots, register designated candidates, and track real-time voter turnout.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permissions');
    }
};
