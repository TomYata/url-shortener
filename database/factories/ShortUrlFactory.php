<?php

namespace Database\Factories;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'original_url' => $this->faker->url(),
            'short_url' => substr($this->faker->uuid(), 0, 6),
            'usage_count' => 0,
            'is_active' => true,
        ];
    }
}
