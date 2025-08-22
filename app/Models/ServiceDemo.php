<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class ServiceDemo extends Model
{
    // Disable database operations
    public $timestamps = false;
    protected $table = 'services_demo'; // Non-existent table
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'category',
        'price',
        'status',
        'country_id',
        'created_at'
    ];

    protected $casts = [
        'status' => 'integer',
        'price' => 'decimal:2'
    ];

    // Status constants
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * DEMO DATA - Replace database queries with fake data
     */
    public static function getDemoData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Web Development',
                'description' => 'Custom website development using modern technologies',
                'category' => 'Development',
                'price' => 299.99,
                'status' => 1,
                'country_id' => 1,
                'created_at' => '2024-01-15 10:30:00'
            ],
            [
                'id' => 2,
                'name' => 'Mobile App Development',
                'description' => 'iOS and Android mobile application development',
                'category' => 'Development',
                'price' => 499.99,
                'status' => 1,
                'country_id' => 1,
                'created_at' => '2024-01-16 11:45:00'
            ],
            [
                'id' => 3,
                'name' => 'Digital Marketing',
                'description' => 'SEO, SEM, Social Media Marketing services',
                'category' => 'Marketing',
                'price' => 199.99,
                'status' => 1,
                'country_id' => 2,
                'created_at' => '2024-01-17 09:15:00'
            ],
            [
                'id' => 4,
                'name' => 'Graphic Design',
                'description' => 'Logo design, branding, and print design services',
                'category' => 'Design',
                'price' => 149.99,
                'status' => 0, // Disabled for testing
                'country_id' => 1,
                'created_at' => '2024-01-18 14:20:00'
            ],
            [
                'id' => 5,
                'name' => 'Content Writing',
                'description' => 'Blog posts, copywriting, and content creation',
                'category' => 'Writing',
                'price' => 99.99,
                'status' => 1,
                'country_id' => 3,
                'created_at' => '2024-01-19 16:30:00'
            ],
            [
                'id' => 6,
                'name' => 'E-commerce Solutions',
                'description' => 'Online store development and management',
                'category' => 'Development',
                'price' => 699.99,
                'status' => 1,
                'country_id' => 1,
                'created_at' => '2024-01-20 08:45:00'
            ],
            [
                'id' => 7,
                'name' => 'UI/UX Design',
                'description' => 'User interface and user experience design',
                'category' => 'Design',
                'price' => 349.99,
                'status' => 1,
                'country_id' => 2,
                'created_at' => '2024-01-21 13:10:00'
            ],
            [
                'id' => 8,
                'name' => 'Cloud Services',
                'description' => 'AWS, Azure, Google Cloud setup and management',
                'category' => 'Infrastructure',
                'price' => 449.99,
                'status' => 0, // Disabled for testing
                'country_id' => 1,
                'created_at' => '2024-01-22 10:55:00'
            ]
        ];
    }

    /**
     * Static methods for demo functionality
     */
    public static function getEnabled()
    {
        return collect(self::getDemoData())
            ->where('status', self::STATUS_ENABLED)
            ->map(function ($item) {
                return new static($item);
            });
    }

    public static function searchDemo($term = '')
    {
        $data = collect(self::getDemoData());
        
        if (!empty($term)) {
            $data = $data->filter(function ($item) use ($term) {
                return str_contains(strtolower($item['name']), strtolower($term)) ||
                       str_contains(strtolower($item['description']), strtolower($term)) ||
                       str_contains(strtolower($item['category']), strtolower($term));
            });
        }
        
        return $data->where('status', self::STATUS_ENABLED)
                   ->map(function ($item) {
                       return new static($item);
                   });
    }

    public static function getByCategory($category)
    {
        return collect(self::getDemoData())
            ->where('category', $category)
            ->where('status', self::STATUS_ENABLED)
            ->map(function ($item) {
                return new static($item);
            });
    }

    /**
     * Helper methods
     */
    public function isEnabled()
    {
        return $this->status == self::STATUS_ENABLED;
    }

    public function isDisabled()
    {
        return $this->status == self::STATUS_DISABLED;
    }

    /**
     * Convert to array (for JSON responses)
     */
    public function toArray()
    {
        return $this->attributes;
    }
}