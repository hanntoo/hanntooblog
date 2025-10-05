<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category_name = fake()->sentence(rand(1,3), false);
        return [
            'name' => $category_name,
            'slug' => Str::slug($category_name),
            'color' => fake()->randomElement([
                'bg-red-100',
                'bg-green-100',
                'bg-blue-100',
                'bg-yellow-100',
                'bg-purple-100',
            ]),
        ];
    }
}
