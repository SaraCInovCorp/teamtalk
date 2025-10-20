<?php

use App\Models\User;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to create room', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);
    $policy = new \App\Policies\RoomPolicy();
    expect($policy->create($admin))->toBeTrue();
});

it('denies user to create room', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $policy = new \App\Policies\RoomPolicy();
    expect($policy->create($user))->toBeFalse();
});