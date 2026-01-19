<?php

namespace Database\Factories;

use App\Models\FavoriteList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteListFactory extends Factory
{
    protected $model = FavoriteList::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
        ];
    }
}
