<?php

namespace Database\Seeders;

use App\Models\Plugin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plugins = [
            [
                'name' => 'Progressive Web App',
                'description' => 'This plugins allows you to install your application as a Progressive Web App (PWA) on your device\'s home screen.',
                'version' => '1.0.0',
                'code' => 'pwa',
                'status' => 'active',
            ],
            [
                'name' => 'Quotation',
                'description' => 'This plugins allows you to create and manage quotations. It will add a new menu in sidebar called Quotation.',
                'version' => '1.0.0',
                'code' => 'quotation',
                'status' => 'active',
            ]
        ];

        foreach ($plugins as $plugin) {
            Plugin::create($plugin);
        }
    }
}
