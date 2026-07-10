<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            'NG' => [
                ['name' => 'Lagos', 'code' => 'LA'],
                ['name' => 'Abuja FCT', 'code' => 'FC'],
                ['name' => 'Kano', 'code' => 'KN'],
                ['name' => 'Rivers', 'code' => 'RI'],
                ['name' => 'Oyo', 'code' => 'OY'],
            ],
            'KE' => [
                ['name' => 'Nairobi', 'code' => '30'],
                ['name' => 'Mombasa', 'code' => '28'],
                ['name' => 'Kisumu', 'code' => '17'],
            ],
            'ZA' => [
                ['name' => 'Gauteng', 'code' => 'GP'],
                ['name' => 'Western Cape', 'code' => 'WC'],
                ['name' => 'KwaZulu-Natal', 'code' => 'KZN'],
            ],
            'EG' => [
                ['name' => 'Cairo', 'code' => 'C'],
                ['name' => 'Alexandria', 'code' => 'ALX'],
                ['name' => 'Giza', 'code' => 'GZ'],
            ],
            'GH' => [
                ['name' => 'Greater Accra', 'code' => 'AA'],
                ['name' => 'Ashanti', 'code' => 'AH'],
                ['name' => 'Western', 'code' => 'WP'],
            ],
        ];

        foreach ($states as $countryCode => $countryStates) {
            $country = Country::where('code', $countryCode)->first();

            if (! $country) {
                continue;
            }

            foreach ($countryStates as $state) {
                State::updateOrCreate(
                    ['country_id' => $country->id, 'name' => $state['name']],
                    ['code' => $state['code']]
                );
            }
        }
    }
}
