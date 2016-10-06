<?php namespace App\Jobs\Asimov\Management\Permissions;

use App\Jobs\Job;
use App\Repositories\Asimov\Management\Permissions\PermissionContract;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdatePermissionJob extends Job implements SelfHandling
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
     * Execute the command.
     *
     * @return \App\Models\Permission
     */
    public function handle()
    {
        return $this->permissions->update(\Input::get('id'), \Input::except('id'));
    }

}
