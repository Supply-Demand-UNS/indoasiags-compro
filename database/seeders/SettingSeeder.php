<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'video_url',
                'value' => 'https://www.youtube.com/embed/3nQNiWdeH2Q?v=3nQNiWdeH2Q',
                'validation' => 'required|url'
            ],
            [
                'key' => 'phone_number',
                'value' => '+012-345-6789',
                'validation' => 'required|max:50'
            ],
            [
                'key' => 'email',
                'value' => 'example@gmail.com',
                'validation' => 'required|email'
            ],
            [
                'key' => 'x_url',
                'value' => 'https://x.com/abcd',
                'validation' => 'nullable|url'
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/abcd',
                'validation' => 'nullable|url'
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/addin.rizal',
                'validation' => 'nullable|url'
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/abcd',
                'validation' => 'nullable|url'
            ],

        ];

        foreach ($settings as $setting) {
            $check = \App\Models\Setting::where('key', $setting['key'])->count();
            if ($check == 0) {
                \App\Models\Setting::create($setting);
            }
        }
    }
}
