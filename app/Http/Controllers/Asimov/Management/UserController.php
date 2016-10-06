<?php namespace App\Http\Controllers\Asimov\Management;

use App\Http\Controllers\Asimov\AsimovController;
use App\Http\Requests;
use App\Http\Requests\Asimov\Management\Users\CreateUserRequest;
use App\Http\Requests\Asimov\Management\Users\DeleteUserRequest;
use App\Http\Requests\Asimov\Management\Users\UpdateUserRequest;
use App\Jobs\Asimov\Management\Users\CreateUserJob;
use App\Jobs\Asimov\Management\Users\DeleteUserJob;
use App\Jobs\Asimov\Management\Users\UpdateUserJob;
use App\Repositories\Asimov\Management\Roles\RoleContract;
use App\Repositories\Asimov\Management\Users\UserContract;
use Response;

class UserController extends AsimovController
{

    /**
     * @var UserContract
     */
    protected $users;

    /**
     * @var RoleContract
     */
    protected $roles;

    function __construct(UserContract $users, RoleContract $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return \View::make('asimov.management.users.index', [
            'users' => $this->users->getUsersPaginated(25)->fragment(\Input::get('page', 1)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return \View::make('asimov.management.users.create', [
            'roles' => $this->roles->getAllRoles(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = $this->dispatch(app(CreateUserJob::class));

        \Notification::success(trans('notifications.user_created'));

        return \Redirect::route('management.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        return \View::make('asimov.management.users.edit', [
            'user' => $this->users->findOrThrowException($id, true),
            'roles' => $this->roles->getAllRoles()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return Response
     * @internal param int $id
     */
    public function update(UpdateUserRequest $request)
    {
        $this->dispatch(app(UpdateUserJob::class));

        \Notification::success(trans('notifications.user_updated'));

        if ($request->get('savereturn')) {
            return \Redirect::route('management.users.index');
        } else {
            return \Redirect::route('management.users.edit', [$request->get('id')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param DeleteUserRequest $request
     * @return Response
     */
    public function destroy($id, DeleteUserRequest $request)
    {
        $this->dispatch(app(DeleteUserJob::class));

        \Notification::success(trans('notifications.user_deleted'));

        return \Redirect::route('management.users.index');
    }

}
