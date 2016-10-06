<?php namespace App\Http\Controllers\Asimov\Management;

use App\Http\Controllers\Asimov\AsimovController;
use App\Http\Requests;
use App\Http\Requests\Asimov\Management\Permissions\CreatePermissionRequest;
use App\Http\Requests\Asimov\Management\Permissions\DeletePermissionRequest;
use App\Http\Requests\Asimov\Management\Permissions\UpdatePermissionRequest;
use App\Jobs\Asimov\Management\Permissions\CreatePermissionJob;
use App\Jobs\Asimov\Management\Permissions\DeletePermissionJob;
use App\Jobs\Asimov\Management\Permissions\UpdatePermissionJob;
use App\Repositories\Asimov\Management\Permissions\PermissionContract;
use Response;

class PermissionController extends AsimovController
{

    /**
     * @var PermissionContract
     */
    private $permissions;

    /**
     * @param PermissionContract $permissions
     */
    function __construct(PermissionContract $permissions)
    {
        $this->permissions = $permissions;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return \View::make('asimov.management.permissions.index', [
            'permissions' => $this->permissions->getAllPermissions()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return \View::make('asimov.management.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePermissionRequest $request
     * @return Response
     */
    public function store(CreatePermissionRequest $request)
    {
        $this->dispatch(app(CreatePermissionJob::class));

        \Notification::success(trans('notifications.permission_created'));

        return \Redirect::route('management.permissions.index');
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
        return \View::make('asimov.management.permissions.edit', [
            'permission' => $this->permissions->findOrThrowException($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @return Response
     */
    public function update(UpdatePermissionRequest $request)
    {
        $this->dispatch(app(UpdatePermissionJob::class));

        \Notification::success(trans('notifications.permission_updated'));

        return \Redirect::route('management.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param DeletePermissionRequest $request
     * @return Response
     */
    public function destroy($id, DeletePermissionRequest $request)
    {
        $this->dispatch(app(DeletePermissionJob::class));

        \Notification::success(trans('notifications.permission_deleted'));

        return \Redirect::route('management.permissions.index');
    }

}
