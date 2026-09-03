<?php

use App\Models\Committee;
use App\Models\Title;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Default Standing Committees
        $audit = Committee::firstOrCreate(['name' => 'Audit Committee']);
        $risk = Committee::firstOrCreate(['name' => 'Risk Committee']);
        $nominations = Committee::firstOrCreate(['name' => 'Nominations Committee']);

        // Insert some default titles under these standing committees
        Title::firstOrCreate([
            'group' => 'Audit Committee',
            'title' => 'Audit Chairperson',
        ]);
        Title::firstOrCreate([
            'group' => 'Audit Committee',
            'title' => 'Audit Member',
        ]);

        Title::firstOrCreate([
            'group' => 'Risk Committee',
            'title' => 'Risk Chairperson',
        ]);
        Title::firstOrCreate([
            'group' => 'Risk Committee',
            'title' => 'Risk Officer',
        ]);

        Title::firstOrCreate([
            'group' => 'Nominations Committee',
            'title' => 'Nominations Chairperson',
        ]);
        Title::firstOrCreate([
            'group' => 'Nominations Committee',
            'title' => 'Nominations Member',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional
    }
};
