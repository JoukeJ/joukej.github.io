<?php namespace App\Jobs\Asimov\Management\Users;

use App\Jobs\Job;
use App\Repositories\Asimov\Management\Users\UserContract;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateUserJob extends Job implements SelfHandling
{

    /**
     * @var UserContract
     */
    private $users;

    /**
     * Create a new command instance.
     *
     * @param UserContract $users
     */
    public function __construct(UserContract $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        return $this->users->create(\Input::except(['roles']), \Input::get('roles', []));
    }

}
