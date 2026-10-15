<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('QUILL_ADMIN_EMAIL');
        $adminPassword = env('QUILL_ADMIN_PASSWORD');

        if (! $adminEmail || ! $adminPassword) {
            throw new RuntimeException(
                'Set QUILL_ADMIN_EMAIL and QUILL_ADMIN_PASSWORD in .env before running the database seeder.'
            );
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('QUILL_ADMIN_NAME', 'Quill Motors'),
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]
        );

        $categories = [
            ['name' => 'Performance', 'slug' => 'performance'],
            ['name' => 'Luxury SUV', 'slug' => 'luxury-suv'],
            ['name' => 'Grand Tourer', 'slug' => 'grand-tourer'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $cars = [
            [
                'category' => 'performance',
                'make' => 'Porsche',
                'model' => '911 Carrera',
                'year' => 2024,
                'price' => 8500000,
                'mileage' => 4200,
                'transmission' => 'Automatic',
                'fuel_type' => 'Gasoline',
                'condition' => 'Used',
                'description' => 'A precise, driver-focused sports car with everyday usability.',
            ],
            [
                'category' => 'luxury-suv',
                'make' => 'BMW',
                'model' => 'X5 xDrive40i',
                'year' => 2025,
                'price' => 6200000,
                'mileage' => 1800,
                'transmission' => 'Automatic',
                'fuel_type' => 'Gasoline',
                'condition' => 'Used',
                'description' => 'Confident long-distance comfort with a spacious premium interior.',
            ],
            [
                'category' => 'grand-tourer',
                'make' => 'Mercedes-Benz',
                'model' => 'AMG GT',
                'year' => 2024,
                'price' => 9800000,
                'mileage' => 2500,
                'transmission' => 'Automatic',
                'fuel_type' => 'Gasoline',
                'condition' => 'Used',
                'description' => 'A refined grand tourer built for memorable open-road journeys.',
            ],
        ];

        foreach ($cars as $car) {
            $category = Category::where('slug', $car['category'])->firstOrFail();
            unset($car['category']);
            Car::updateOrCreate(
                ['make' => $car['make'], 'model' => $car['model'], 'year' => $car['year']],
                [...$car, 'category_id' => $category->id, 'status' => 'available']
            );
        }
    }
}
