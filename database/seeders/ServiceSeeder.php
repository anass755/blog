<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Web Development',
                'description' => 'Custom website development using modern technologies',
                'is_active' => true
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'iOS and Android mobile application development',
                'is_active' => true
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'SEO, SEM, Social Media Marketing services',
                'is_active' => true
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Logo design, branding, and print design services',
                'is_active' => true
            ],
            [
                'name' => 'Content Writing',
                'description' => 'Blog posts, copywriting, and content creation',
                'is_active' => true
            ],
            [
                'name' => 'E-commerce Solutions',
                'description' => 'Online store development and management',
                'is_active' => true
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'User interface and user experience design',
                'is_active' => true
            ],
            [
                'name' => 'Cloud Services',
                'description' => 'AWS, Azure, Google Cloud setup and management',
                'is_active' => true
            ],
            [
                'name' => 'Data Analytics',
                'description' => 'Business intelligence and data reporting',
                'is_active' => true
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Security audits and implementation services',
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}