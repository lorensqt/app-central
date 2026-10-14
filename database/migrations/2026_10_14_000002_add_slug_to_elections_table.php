<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Election;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        // Generate slugs for existing elections
        foreach (Election::all() as $election) {
            $baseSlug = Str::slug($election->title) ?: 'election-' . $election->id;
            $slug = $baseSlug;
            $count = 1;

            while (Election::where('slug', $slug)->where('id', '!=', $election->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $election->slug = $slug;
            $election->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
