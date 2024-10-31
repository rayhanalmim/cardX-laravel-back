<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;

class CardPolicy
{
    /**
     * Determine whether the user can view the card.
     */
    public function view(User $user, Card $card)
    {
        return $user->id === $card->user_id; // Check if the user owns the card
    }

    /**
     * Determine whether the user can update the card.
     */
    public function update(User $user, Card $card)
    {
        return $user->id === $card->user_id; // Check if the user owns the card
    }

    /**
     * Determine whether the user can delete the card.
     */
    public function delete(User $user, Card $card)
    {
        return $user->id === $card->user_id; // Check if the user owns the card
    }
}
