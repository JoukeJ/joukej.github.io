<?php namespace App\Jobs\Asimov\Profile;

use App\Events\Profile\ProfileWasUpdated;
use App\Jobs\Asimov\User\ChangeEmailJob;
use App\Jobs\Job;
use App\Repositories\Asimov\User\UserContract;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateProfileJob extends Job implements SelfHandling
{
    use DispatchesJobs;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var UserContract
     */
    private $users;

    /**
     * @param Guard $guard
     * @param UserContract $users
     */
    public function __construct(Guard $guard, UserContract $users)
    {
        $this->guard = $guard;
        $this->users = $users;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->users->updateProfile($this->guard->user()->id, \Input::all());

        if (\Input::get('email') !== $user->email) {
            $this->dispatch(app(ChangeEmailJob::class,
                [$user, \Input::get('email')]));
        }

        \Event::fire(new ProfileWasUpdated($user));
    }

}
