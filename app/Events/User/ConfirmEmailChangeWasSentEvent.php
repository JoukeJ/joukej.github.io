<?php namespace App\Events\User;

use App\Events\Event;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class ConfirmEmailChangeWasSentEvent extends Event
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var
     */
    public $email;

    /**
     * @param User $user
     * @param $email
     */
    function __construct(User $user, $email)
    {
        $this->user = $user;
        $this->email = $email;
    }


    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
