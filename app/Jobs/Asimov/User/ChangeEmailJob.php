<?php namespace App\Jobs\Asimov\User;

use App\Events\User\ConfirmEmailChangeWasSentEvent;
use App\Jobs\Asimov\Mail\QueueMailJob;
use App\Jobs\Job;
use App\Models\Email;
use App\Models\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ChangeEmailJob extends Job implements SelfHandling
{
    use DispatchesJobs;

    /**
     * @var User
     */
    private $user;

    /**
     * @var
     */
    private $email;

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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $newEmailAddress = $this->email;

        $email = Email::create([
            'to' => $newEmailAddress,
            'subject' => trans('management/user.confirm_email.subject'),
            'body' => 'emails.' . $user->language . '.changemail',
            'status' => 'new'
        ]);


        $emailchange = $this->createEmailchange($user, $newEmailAddress);

        $params = [
            'url' => \URL::route('user.confirm_email', [$emailchange->token])
        ];

        $this->dispatch(app(QueueMailJob::class, ['email' => $email, 'params' => $params]));

        \Event::fire(new ConfirmEmailChangeWasSentEvent($user, $newEmailAddress));
    }


    /**
     * @param User $user
     * @param $email
     * @return User\Emailchange
     */
    protected function createEmailchange(User $user, $email)
    {
        $emailchange = User\Emailchange::create([
            'user_id' => $user->id,
            'token' => $this->getToken(),
            'email' => $email
        ]);

        return $emailchange;
    }

    /**
     * @return string
     */
    protected function getToken()
    {
        return str_random(128);
    }

}
