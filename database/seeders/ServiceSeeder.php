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
                'status' => 1  // Enabled
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'iOS and Android mobile application development',
                'status' => 1  // Enabled
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'SEO, SEM, Social Media Marketing services',
                'status' => 1  // Enabled
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Logo design, branding, and print design services',
                'status' => 0  // Disabled (for testing)
            ],
            [
                'name' => 'Content Writing',
                'description' => 'Blog posts, copywriting, and content creation',
                'status' => 1  // Enabled
            ],
            [
                'name' => 'E-commerce Solutions',
                'description' => 'Online store development and management',
                'status' => 1  // Enabled
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'User interface and user experience design',
                'status' => 1  // Enabled
            ],
            [
                'name' => 'Cloud Services',
                'description' => 'AWS, Azure, Google Cloud setup and management',
                'status' => 0  // Disabled (for testing)
            ],
            [
                'name' => 'Data Analytics',
                'description' => 'Business intelligence and data reporting',
                'status' => 1  // Enabled
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Security audits and implementation services',
                'status' => 1  // Enabled
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}