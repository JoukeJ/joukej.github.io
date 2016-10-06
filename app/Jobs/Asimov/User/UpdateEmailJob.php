<?php namespace App\Jobs\Asimov\User;

use App\Jobs\Job;
use App\Models\User\Emailchange;
use App\Repositories\Asimov\Management\Users\UserContract;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdateEmailJob extends Job implements SelfHandling
{

    /**
     * @var Emailchange
     */
    private $emailchange;

    /**
     * @var UserContract
     */
    private $users;

    /**
     * @param Emailchange $emailchange
     * @param UserContract $users
     */
    public function __construct(Emailchange $emailchange, UserContract $users)
    {
        $this->emailchange = $emailchange;
        $this->users = $users;
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        if ($this->users->changeEmail($this->emailchange->user_id, $this->emailchange->email)) {
            $this->emailchange->valid = false;
            $this->emailchange->save();

            return true;
        }

        return false;
    }

}
