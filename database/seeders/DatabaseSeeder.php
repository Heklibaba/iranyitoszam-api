<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\City;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $pest = County::firstOrCreate([
            'name' => 'Pest'
        ]);

        $fejer = County::firstOrCreate([
            'name' => 'Fejér'
        ]);

        City::firstOrCreate([
            'name' => 'Vác',
            'county_id' => $pest->id
        ]);

        City::firstOrCreate([
            'name' => 'Szentendre',
            'county_id' => $pest->id
        ]);

        City::firstOrCreate([
            'name' => 'Székesfehérvár',
            'county_id' => $fejer->id
        ]);

        City::firstOrCreate([
            'name' => 'Dunaújváros',
            'county_id' => $fejer->id
        ]);
        User::updateOrCreate(
            ['email' => 'teszt@example.com'],
            [
                'name' => 'Teszt Elek',
                'password' => Hash::make('Teszt123456!')
            ]
);
    }
    
}