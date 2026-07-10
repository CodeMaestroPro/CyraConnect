<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Lagos' => ['Lagos', 'Ikeja', 'Lekki'],
            'Abuja FCT' => ['Abuja', 'Gwagwalada'],
            'Kano' => ['Kano'],
            'Rivers' => ['Port Harcourt'],
            'Oyo' => ['Ibadan'],
            'Nairobi' => ['Nairobi', 'Westlands'],
            'Mombasa' => ['Mombasa'],
            'Kisumu' => ['Kisumu'],
            'Gauteng' => ['Johannesburg', 'Pretoria', 'Sandton'],
            'Western Cape' => ['Cape Town', 'Stellenbosch'],
            'KwaZulu-Natal' => ['Durban'],
            'Cairo' => ['Cairo', 'Nasr City'],
            'Alexandria' => ['Alexandria'],
            'Giza' => ['Giza'],
            'Greater Accra' => ['Accra', 'Tema'],
            'Ashanti' => ['Kumasi'],
            'Western' => ['Takoradi'],
        ];

        foreach ($cities as $stateName => $cityNames) {
            $state = State::where('name', $stateName)->first();

            if (! $state) {
                continue;
            }

            foreach ($cityNames as $cityName) {
                City::updateOrCreate(
                    ['state_id' => $state->id, 'name' => $cityName]
                );
            }
        }
    }
}
