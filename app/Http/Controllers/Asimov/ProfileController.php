<?php namespace App\Http\Controllers\Asimov;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Asimov\Profile\UpdateProfileRequest;
use App\Jobs\Asimov\Profile\UpdateProfileJob;
use App\Repositories\Asimov\User\UserContract;
use Illuminate\Contracts\Auth\Guard;

class ProfileController extends Controller
{
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
    function __construct(Guard $guard, UserContract $users)
    {
        $this->guard = $guard;
        $this->users = $users;
    }


    /**
     * @return Response
     */
    public function me()
    {
        return $this->show($this->guard->user()->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return \View::make('frontend.profile.show', [
            'user' => $this->users->findOrThrowException($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return \View::make('frontend.profile.edit', [
            'user' => $this->guard->user()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProfileRequest $request
     * @return Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $this->dispatch(app(UpdateProfileJob::class));

        return \Redirect::route('frontend.profile.me');
    }

}
