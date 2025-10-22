<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Room $room): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Room $room): bool
    {
        return $user->isAdmin() || $room->users()->where('user_id', $user->id)->wherePivot('role_in_room', 'admin')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $room->created_by || 
            $room->users()->where('user_id', $user->id)->wherePivot('role_in_room', 'admin')->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Room $room): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Room $room): bool
    {
        return false;
    }

    public function invite(User $user, Room $room)
    {
        return $user->isAdmin() || $room->users()->where('user_id', $user->id)->wherePivot('role_in_room', 'admin')->exists();
    }

    public function editInfo(User $user, Room $room): bool
    {
        if ($user->isAdmin()) {
            return $room->allow_edit_description;
        }

        $pivot = $room->users()->where('user_id', $user->id)->first()?->pivot;
        if ($pivot && !$pivot->blocked && $pivot->role_in_room === 'admin') {
            return $room->allow_edit_description;
        }

        return false;
    }


    public function attachFiles(User $user, Room $room): bool
    {
        if (!($user->isAdmin() || $room->users()->where('user_id', $user->id)->wherePivot('role_in_room', 'admin')->exists())) {
            return false;
        }

        return $room->allow_attachment;
    }

    public function sendMessages(User $user, Room $room): bool
    {
        if (!($user->isAdmin() || $room->users()->where('user_id', $user->id)->wherePivot('role_in_room', 'admin')->exists())) {
            return false;
        }

        return $room->allow_send_messages;
    }

    public function before(User $user, $room = null)
    {
        if (! $room instanceof Room) {
            return null; 
        }

        $pivot = $room->users()->where('user_id', $user->id)->first()?->pivot;

        if ($pivot && $pivot->blocked) {
            return false; 
        }

        return null; 
    }


    public function leave(User $user, Room $room): bool
    {
        $pivot = $room->users()->where('user_id', $user->id)->first()?->pivot;
        return $pivot !== null;
    }

}
