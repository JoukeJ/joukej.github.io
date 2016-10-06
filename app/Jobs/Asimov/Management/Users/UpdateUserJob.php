<?php namespace App\Jobs\Asimov\Management\Users;

use App\Jobs\Asimov\User\ChangeEmailJob;
use App\Jobs\Job;
use App\Models\User;
use App\Repositories\Asimov\Management\Users\UserContract;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateUserJob extends Job implements SelfHandling
{
    use DispatchesJobs;

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
     *
     * @return User
     */
    public function handle()
    {
        $exceptPassword = ['password'];

        // if password is not empty, exclude password from except so it gets stored
        if (\Input::has('change_password')) {
            $exceptPassword = [];
        }

        $user = $this->users->update(
            \Input::get('id'),
            \Input::except(array_merge_recursive(['id', 'roles', 'email'], $exceptPassword)),
            \Input::get('roles'),
            []
        );

        // If mail is changed, send out an email for confirmation (to the new email address).
        if (\Input::get('email') !== $user->email) {
            $this->dispatch(app(ChangeEmailJob::class,
                [$user, \Input::get('email')]));
        }

        return $user;
    }

}
