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
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft'); // draft, active, closed
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('election_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->onDelete('cascade');
            $table->string('name');
            $table->integer('max_votes')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('election_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('election_positions')->onDelete('cascade');
            $table->string('name');
            $table->string('party_affiliation')->nullable();
            $table->string('avatar_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('election_voters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('division')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->dateTime('voted_at')->nullable();
            $table->timestamps();

            // Prevent double registration/voting logic at DB level
            $table->unique(['election_id', 'email']);
        });

        Schema::create('election_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('election_voters')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('election_positions')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('election_candidates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('election_votes');
        Schema::dropIfExists('election_voters');
        Schema::dropIfExists('election_candidates');
        Schema::dropIfExists('election_positions');
        Schema::dropIfExists('elections');
    }
};
