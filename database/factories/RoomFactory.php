<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Room;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        $imageNumber = $this->faker->numberBetween(1, 1000);

        return [
            'name' => $this->faker->words(2, true),
            'avatar' => "https://picsum.photos/150/200?image={$imageNumber}",
            'is_private' => $this->faker->boolean(30),
            'created_by' => User::factory(),
        ];
    }
}

