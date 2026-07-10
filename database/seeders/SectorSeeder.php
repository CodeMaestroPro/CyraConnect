<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            'Fintech', 'Healthtech', 'Edtech', 'Agtech', 'Cleantech', 'E-commerce',
            'Logistics', 'Proptech', 'Insurtech', 'Legaltech', 'HR Tech', 'Cybersecurity',
            'AI & Machine Learning', 'Blockchain', 'IoT', 'SaaS', 'Marketplace',
            'Media & Entertainment', 'Travel & Tourism', 'Food & Beverage',
            'Renewable Energy', 'Telecommunications', 'Govtech', 'Social Impact',
        ];

        foreach ($sectors as $name) {
            Sector::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            );
        }
    }
}
