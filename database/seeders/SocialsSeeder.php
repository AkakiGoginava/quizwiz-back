<?php

namespace Database\Seeders;

use App\Models\Social;
use Illuminate\Database\Seeder;

class SocialsSeeder extends Seeder
{
    public function run(): void
    {
        $socialsDefault = [
            ['key' => 'email',     'value' => 'info@example.com'],
            ['key' => 'phone',     'value' => '+123456789'],
            ['key' => 'facebook',  'value' => 'https://fb.com'],
            ['key' => 'linkedin', 'value' => 'https://linkedin.com'],
        ];

        foreach ($socialsDefault as $data) {
            Social::factory()->create($data);
        }
    }
}
