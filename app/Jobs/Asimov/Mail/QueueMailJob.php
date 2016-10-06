<?php namespace App\Jobs\Asimov\Mail;

use App\Jobs\Job;
use App\Models\Email;
use App\Queue\SendMail;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Queue;

class QueueMailJob extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var array
     */
    private $params;

    /**
     * @param $email
     * @param $params
     */
    function __construct(Email $email, $params)
    {
        $this->email = $email;
        $this->params = $params;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        Queue::push(SendMail::class, ['email' => $this->email, 'params' => $this->params]);
    }

}
