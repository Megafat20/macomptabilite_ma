<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Benkirane Hicham',
            'email' => 'hicham.benkirane@assur-saj.ma',
            'password' => bcrypt('123456789'),
        ]);

        $companies = [
            'CABINET BENKIRANE ASSUR SAJ',
            'CABINET BENKIRANE ASSUR RABAT',
            'FIRST LEVE SERVICE MAROC',
            'TELEVOICE MAROC',
            'DGICICHRONO SERVICE',
            'MAROC EXCURTIONES MARITIMOS',
        ];

        foreach ($companies as $company) {
            \App\Models\Company::create(['nom' => $company]);
        }
    }
}
