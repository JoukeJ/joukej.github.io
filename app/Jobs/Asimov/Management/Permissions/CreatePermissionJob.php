<?php namespace App\Jobs\Asimov\Management\Permissions;

use App\Jobs\Job;
use App\Models\Permission;
use App\Repositories\Asimov\Management\Permissions\PermissionContract;
use Illuminate\Contracts\Bus\SelfHandling;

class CreatePermissionJob extends Job implements SelfHandling
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
     * @return Permission
     */
    public function handle()
    {
        return $this->permissions->create(\Input::all());
    }

}
