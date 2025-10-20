<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Message;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        
        User::factory()->count(3)->create([
            'role' => 'admin'
        ])->each(function ($user) {
            /*
            $rooms = Room::factory(rand(1, 3))->create(['created_by' => $user->id]);

            foreach ($rooms as $room) {
                $room->users()->attach($user->id, ['role_in_room' => 'admin', 'joined_at' => now()]);

                Message::factory(rand(5, 10))->create([
                    'room_id' => $room->id,
                    'sender_id' => $user->id,
                ]);
            }
                */
        });

        
        User::factory()->count(7)->create([
            'role' => 'user'
        ])->each(function ($user) {
        /*
            $rooms = Room::inRandomOrder()->take(rand(1, 2))->get();

            foreach ($rooms as $room) {
                $room->users()->attach($user->id, ['role_in_room' => 'member', 'joined_at' => now()]);

                Message::factory(rand(1, 5))->create([
                    'room_id' => $room->id,
                    'sender_id' => $user->id,
                ]);
            }
                */
        });
    }
}
