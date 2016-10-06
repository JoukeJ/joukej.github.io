<?php namespace App\Jobs\Asimov\Management\Permissions;

use App\Jobs\Job;
use App\Repositories\Asimov\Management\Permissions\PermissionContract;
use Illuminate\Contracts\Bus\SelfHandling;

class DeletePermissionJob extends Job implements SelfHandling
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
     * @return void
     */
    public function handle()
    {
        $this->permissions->delete(\Route::current()->getParameter('permissions'));
    }

}
