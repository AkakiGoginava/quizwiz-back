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
            ['key' => 'tel',     'value' => '+123456789'],
            ['key' => 'facebook',  'value' => 'https://fb.com'],
            ['key' => 'linkedin', 'value' => 'https://linkedin.com'],
        ];

        foreach ($socialsDefault as $data) {
            Social::create($data);
        }
    }
}
