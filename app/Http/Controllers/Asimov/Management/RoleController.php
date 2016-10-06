<?php namespace App\Http\Controllers\Asimov\Management;

use App\Http\Controllers\Asimov\AsimovController;
use App\Http\Requests;
use App\Http\Requests\Asimov\Management\Roles\CreateRoleRequest;
use App\Http\Requests\Asimov\Management\Roles\DeleteRoleRequest;
use App\Http\Requests\Asimov\Management\Roles\UpdateRoleRequest;
use App\Jobs\Asimov\Management\Roles\CreateRoleJob;
use App\Jobs\Asimov\Management\Roles\DeleteRoleJob;
use App\Jobs\Asimov\Management\Roles\UpdateRoleJob;
use App\Repositories\Asimov\Management\Permissions\PermissionContract;
use App\Repositories\Asimov\Management\Roles\RoleContract;
use Response;

class RoleController extends AsimovController
{

    /**
     * @var RoleContract
     */
    private $roles;

    /**
     * @var PermissionContract
     */
    private $permissions;

    /**
     * @param RoleContract $roles
     * @param PermissionContract $permissions
     */
    function __construct(RoleContract $roles, PermissionContract $permissions)
    {
        $this->roles = $roles;
        $this->permissions = $permissions;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return \View::make('asimov.management.roles.index', [
            'roles' => $this->roles->getAllRoles(),
            'permissions' => $this->permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return \View::make('asimov.management.roles.create', [
            'permissions' => $this->permissions->getAllPermissions()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRoleRequest $request
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {
        $this->dispatch(app(CreateRoleJob::class));

        \Notification::success(trans('notifications.role_created'));

        return \Redirect::route('management.roles.index');
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
        return \View::make('asimov.management.roles.edit', [
            'role' => $this->roles->findOrThrowException($id, true),
            'permissions' => $this->permissions->getAllPermissions()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateRoleRequest $request
     * @return Response
     * @internal param int $id
     */
    public function update(UpdateRoleRequest $request)
    {
        $this->dispatch(app(UpdateRoleJob::class));

        \Notification::success(trans('notifications.role_updated'));

        return \Redirect::route('management.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param DeleteRoleRequest $request
     * @return Response
     */
    public function destroy($id, DeleteRoleRequest $request)
    {
        $this->dispatch(app(DeleteRoleJob::class));

        \Notification::success(trans('notifications.role_deleted'));

        return \Redirect::route('management.roles.index');
    }

}
