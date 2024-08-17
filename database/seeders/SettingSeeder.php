<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'image_presence',
            'value' => 'false'
        ]);

        Setting::create([
            'key' => 'min_presence_percentage',
            'value' => '80'
        ]);

        Setting::create([
            'key' => 'min_average_feedback',
            'value' => '4.0'
        ]);
    }
}
