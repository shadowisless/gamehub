<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────
        User::create([
            'name'     => 'Admin GameHub',
            'username' => 'admin',
            'email'    => 'admin@gamehub.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        // ── User Biasa ─────────────────────────────────────
        User::create([
            'name'     => 'Budi Gamer',
            'username' => 'budi',
            'email'    => 'budi@gamehub.com',
            'password' => bcrypt('password123'),
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Sari Player',
            'username' => 'sari',
            'email'    => 'sari@gamehub.com',
            'password' => bcrypt('password123'),
            'role'     => 'user',
        ]);

        // ── Kategori ───────────────────────────────────────
        $categories = [
            ['name' => 'Action',     'slug' => 'action'],
            ['name' => 'RPG',        'slug' => 'rpg'],
            ['name' => 'Strategy',   'slug' => 'strategy'],
            ['name' => 'Sports',     'slug' => 'sports'],
            ['name' => 'Horror',     'slug' => 'horror'],
            ['name' => 'Simulation', 'slug' => 'simulation'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ── Games ──────────────────────────────────────────
        $games = [
            [
                'title'        => 'Shadow Chronicles',
                'developer'    => 'DarkStar Studio',
                'publisher'    => 'GameHub Publishing',
                'description'  => 'Game RPG epik dengan dunia open world yang luas dan penuh petualangan.',
                'price'        => 149000,
                'category_id'  => 2, // RPG
                'release_date' => '2024-01-15',
                'system_requirements' => ['OS' => 'Windows 10', 'RAM' => '8GB', 'GPU' => 'GTX 1060'],
            ],
            [
                'title'        => 'Neon Striker',
                'developer'    => 'PixelForge',
                'publisher'    => 'PixelForge',
                'description'  => 'Game action futuristik dengan grafis neon yang memukau.',
                'price'        => 99000,
                'category_id'  => 1, // Action
                'release_date' => '2024-03-10',
                'system_requirements' => ['OS' => 'Windows 10', 'RAM' => '4GB', 'GPU' => 'GTX 960'],
            ],
            [
                'title'        => 'Farm Paradise',
                'developer'    => 'GreenLeaf Games',
                'publisher'    => 'GreenLeaf Games',
                'description'  => 'Bangun dan kelola pertanian impianmu di dunia yang damai.',
                'price'        => 79000,
                'category_id'  => 6, // Simulation
                'release_date' => '2023-11-20',
                'system_requirements' => ['OS' => 'Windows 7', 'RAM' => '4GB', 'GPU' => 'GTX 750'],
            ],
            [
                'title'        => 'Warfront Tactics',
                'developer'    => 'IronShield Dev',
                'publisher'    => 'IronShield Dev',
                'description'  => 'Game strategi perang dengan sistem taktik yang mendalam.',
                'price'        => 129000,
                'category_id'  => 3, // Strategy
                'release_date' => '2024-02-28',
                'system_requirements' => ['OS' => 'Windows 10', 'RAM' => '8GB', 'GPU' => 'GTX 1050'],
            ],
            [
                'title'        => 'Dark Asylum',
                'developer'    => 'NightOwl Studios',
                'publisher'    => 'NightOwl Studios',
                'description'  => 'Game horror survival di rumah sakit jiwa yang mencekam.',
                'price'        => 89000,
                'category_id'  => 5, // Horror
                'release_date' => '2024-04-01',
                'system_requirements' => ['OS' => 'Windows 10', 'RAM' => '8GB', 'GPU' => 'GTX 1060'],
            ],
            [
                'title'        => 'Super League Football',
                'developer'    => 'SportZone',
                'publisher'    => 'SportZone',
                'description'  => 'Simulasi sepak bola paling realistis dengan lisensi tim dunia.',
                'price'        => 199000,
                'category_id'  => 4, // Sports
                'release_date' => '2023-09-01',
                'system_requirements' => ['OS' => 'Windows 10', 'RAM' => '8GB', 'GPU' => 'RTX 2060'],
            ],
            [
                'title'        => 'Dragon Quest Zero',
                'developer'    => 'MythByte',
                'publisher'    => 'MythByte',
                'description'  => 'Petualangan seorang ksatria melawan naga legendaris.',
                'price'        => 0,
                'category_id'  => 2, // RPG
                'release_date' => '2024-05-01',
                'system_requirements' => ['OS' => 'Windows 7', 'RAM' => '4GB', 'GPU' => 'GTX 750'],
            ],
            [
                'title'        => 'Cyber Rebellion',
                'developer'    => 'NeonCode',
                'publisher'    => 'NeonCode',
                'description'  => 'Game action cyberpunk di kota masa depan yang kacau.',
                'price'        => 159000,
                'category_id'  => 1, // Action
                'release_date' => '2024-06-15',
                'system_requirements' => ['OS' => 'Windows 10', 'RAM' => '16GB', 'GPU' => 'RTX 3060'],
            ],
        ];

        foreach ($games as $g) {
            Game::create([
                'title'               => $g['title'],
                'slug'                => Str::slug($g['title']) . '-' . time() . rand(1, 999),
                'developer'           => $g['developer'],
                'publisher'           => $g['publisher'],
                'description'         => $g['description'],
                'price'               => $g['price'],
                'category_id'         => $g['category_id'],
                'release_date'        => $g['release_date'],
                'status'              => 'active',
                'stock'               => 999,
                'system_requirements' => $g['system_requirements'],
            ]);
        }
    }
}