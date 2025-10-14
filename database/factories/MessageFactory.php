<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Room;
use App\Models\User;
use App\Models\Message;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'room_id' => Room::factory(),
            'sender_id' => User::factory(),
            'recipient_id' => null, 
            'content' => $this->faker->sentence(),
            'attachment' => null,
            'is_read' => false,
            'edited_at' => null,
        ];
    }
}

