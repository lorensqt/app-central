<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionCandidate;
use App\Models\ElectionVoter;
use App\Models\ElectionVote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        $admin = User::updateOrCreate(
            ['email' => 'castillojohnlaurence0@gmail.com'],
            [
                'name' => 'John Laurence Castillo',
                'password' => Hash::make('password'),
                'pin' => '123456', // default pin for straightforward testing
            ]
        );

        // Create an additional general user
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'pin' => '1234',
            ]
        );

        // 2. Create a Realistic Corporate Active Election
        $election = Election::create([
            'title' => 'National OPEC Executive Council Election 2026',
            'description' => 'Official standing committee election for selecting national representative officers, directors, and executives of the M Lhuillier OPEC (Organization of Productive Employees Cooperative). This dataset provides a real-world turnout audit across company divisions.',
            'status' => 'active',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(20),
        ]);

        // 3. Define ballot structure (positions)
        $pPresident = $election->positions()->create([
            'name' => 'National President',
            'max_votes' => 1,
            'sort_order' => 1,
        ]);

        $pVP = $election->positions()->create([
            'name' => 'National Vice President',
            'max_votes' => 1,
            'sort_order' => 2,
        ]);

        $pDirector = $election->positions()->create([
            'name' => 'Board Director (Visayas & Mindanao)',
            'max_votes' => 3, // multiple choice ballot!
            'sort_order' => 3,
        ]);

        // 4. Create Candidates
        // Candidates for President
        $cPres1 = $pPresident->candidates()->create([
            'name' => 'Hon. Arnel L. Lhuillier',
            'party_affiliation' => 'OPEC Progressive Coalition',
            'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Arnel',
            'sort_order' => 1,
        ]);

        $cPres2 = $pPresident->candidates()->create([
            'name' => 'Dir. Maria Elena Gomez',
            'party_affiliation' => 'Cooperative Reform Alliance',
            'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Maria',
            'sort_order' => 2,
        ]);

        // Candidates for VP
        $cVP1 = $pVP->candidates()->create([
            'name' => 'Engr. Roberto Santos',
            'party_affiliation' => 'OPEC Progressive Coalition',
            'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Roberto',
            'sort_order' => 1,
        ]);

        $cVP2 = $pVP->candidates()->create([
            'name' => 'Atty. Clara Valenzuela',
            'party_affiliation' => 'Cooperative Reform Alliance',
            'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Clara',
            'sort_order' => 2,
        ]);

        // Candidates for Board Director
        $cDirs = [
            $pDirector->candidates()->create([
                'name' => 'Supervisor Jaime Tecson',
                'party_affiliation' => 'OPEC Progressive Coalition',
                'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Jaime',
                'sort_order' => 1,
            ]),
            $pDirector->candidates()->create([
                'name' => 'Manager Linda Sy',
                'party_affiliation' => 'OPEC Progressive Coalition',
                'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Linda',
                'sort_order' => 2,
            ]),
            $pDirector->candidates()->create([
                'name' => 'Auditor Mateo Alcala',
                'party_affiliation' => 'Independent Grouping',
                'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Mateo',
                'sort_order' => 3,
            ]),
            $pDirector->candidates()->create([
                'name' => 'Officer Vanessa Cruz',
                'party_affiliation' => 'Cooperative Reform Alliance',
                'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Vanessa',
                'sort_order' => 4,
            ]),
            $pDirector->candidates()->create([
                'name' => 'Analyst Arthur Pendelton',
                'party_affiliation' => 'Cooperative Reform Alliance',
                'avatar_path' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Arthur',
                'sort_order' => 5,
            ]),
        ];

        // 5. Create 50 Realistic Sample Voter Profiles
        $divisions = ['IT Department', 'OPEC Visayas Division', 'OPEC Mindanao Division', 'OPEC Luzon Division', 'Human Resources', 'Finance Division', 'Marketing & Media', 'OPEC Executive Suite'];
        $genders = ['Male', 'Female', 'LGBTQ', 'Others'];
        $otherSpecifications = ['Non-binary', 'Agender', 'Genderqueer', 'Prefer not to say'];
        
        $voterNames = [
            'Miguel Ramos', 'Sophia Torralba', 'Joshua Villanueva', 'Isabella Castillo', 'Nathaniel Santos', 
            'Olivia Lopez', 'Gabriel Fernandez', 'Emma Rodriguez', 'Daniel Alcantara', 'Mia Sebastian',
            'Ethan Garcia', 'Ava Martinez', 'Liam Perez', 'Chloe Cruz', 'Alexander Reyes',
            'Zoe Salazar', 'Benjamin Aquino', 'Lily Sanchez', 'Samuel Diaz', 'Grace Tolentino',
            'Lucas Mendoza', 'Natalie Rivera', 'Caleb de Guzman', 'Madison Ventura', 'Owen Pascual',
            'Hannah Flores', 'Andrew Bautista', 'Aria Evangelista', 'Ryan Valdez', 'Audrey Beltran',
            'Julian Serrano', 'Samantha Corpuz', 'Christian Del Rosario', 'Maya Legaspi', 'Jonathan Sevilla',
            'Leah Ocampo', 'Aaron Mangahas', 'Stella Arellano', 'Christopher Laxamana', 'Violeta Solis',
            'David Abad', 'Camila Pineda', 'Joseph Santiago', 'Clara Gonzales', 'Adrian Mercado',
            'Elena Roxas', 'Nicholas Ignacio', 'Giselle Carreon', 'Timothy Quirino', 'Joanna Laurel'
        ];

        foreach ($voterNames as $index => $name) {
            // Seed a realistic email
            $email = str_replace(' ', '', strtolower($name)) . '@mlhuillier.com';
            
            // Random gender weighting
            $randGenderRoll = rand(1, 100);
            if ($randGenderRoll <= 40) {
                $gender = 'Female';
            } elseif ($randGenderRoll <= 80) {
                $gender = 'Male';
            } elseif ($randGenderRoll <= 93) {
                $gender = 'LGBTQ';
            } else {
                $gender = $otherSpecifications[array_rand($otherSpecifications)];
            }

            // Weighted age (more heavily skewed towards young-to-mid career 25-45)
            $ageRoll = rand(1, 100);
            if ($ageRoll <= 15) {
                $age = rand(19, 25); // Youth
            } elseif ($ageRoll <= 60) {
                $age = rand(26, 35); // Young Adults
            } elseif ($ageRoll <= 85) {
                $age = rand(36, 45); // Mid-Career
            } elseif ($ageRoll <= 96) {
                $age = rand(46, 60); // Experienced
            } else {
                $age = rand(61, 68); // Senior
            }

            // Select random division
            $division = $divisions[array_rand($divisions)];

            // Create Voter Account
            $voter = $election->voters()->create([
                'email' => $email,
                'division' => $division,
                'gender' => $gender,
                'age' => $age,
                'voted_at' => null, // default to null, then we simulate votes below
            ]);

            // Simulate voting for approximately 64% of voters (32 out of 50)
            if ($index < 32) {
                $voter->update([
                    'voted_at' => now()->subDays(rand(0, 4))->subMinutes(rand(1, 1400))
                ]);

                // Record simulated votes
                // 1. Vote for President (1 max)
                $votedPres = rand(1, 2) === 1 ? $cPres1 : $cPres2;
                ElectionVote::create([
                    'voter_id' => $voter->id,
                    'position_id' => $pPresident->id,
                    'candidate_id' => $votedPres->id,
                ]);

                // 2. Vote for VP (1 max)
                $votedVP = rand(1, 2) === 1 ? $cVP1 : $cVP2;
                ElectionVote::create([
                    'voter_id' => $voter->id,
                    'position_id' => $pVP->id,
                    'candidate_id' => $votedVP->id,
                ]);

                // 3. Vote for Director (up to 3 selections)
                // Select 1 to 3 random candidates
                $numSelections = rand(1, 3);
                $selectedDirCandidates = (array) array_rand($cDirs, $numSelections);
                
                foreach ($selectedDirCandidates as $candIndex) {
                    $candidateObj = $cDirs[$candIndex];
                    ElectionVote::create([
                        'voter_id' => $voter->id,
                        'position_id' => $pDirector->id,
                        'candidate_id' => $candidateObj->id,
                    ]);
                }
            }
        }
    }
}
