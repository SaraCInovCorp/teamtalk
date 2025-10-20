<?php

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates message with content and sender', function () {
    $user = User::factory()->create();
    $message = Message::create([
        'sender_id' => $user->id,
        'content' => 'Hello Pest',
    ]);
    expect($message->content)->toBe('Hello Pest');
    expect($message->sender->id)->toBe($user->id);
});
