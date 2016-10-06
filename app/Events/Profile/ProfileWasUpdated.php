<?php namespace App\Events\Profile;

use App\Events\Event;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class ProfileWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new event instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}
