<?php namespace App\Http\Controllers\Asimov;

use App\Jobs\Asimov\User\UpdateEmailJob;
use App\Models\User\Emailchange;

class UserController extends AsimovController
{
    /**
     * Try to validate the user email change request
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getConfirmEmail($token)
    {
        $emailchange = Emailchange::whereToken($token)
            ->whereValid(true)
            ->first();

        $result = $this->dispatch(app(UpdateEmailJob::class, [$emailchange]));

        if ($result === true) {
            // @todo display notification (success)

            if (\Auth::check() && \Auth::user()->id === $emailchange->user_id) {
                return \Redirect::route('frontend.profile.me');
            } else {
                return \Redirect::route('home');
            }
        } else {
            // @todo show error in view?
        }
    }

}
