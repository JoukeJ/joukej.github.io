<?php namespace App\Jobs\Asimov\Management\Roles;

use App\Jobs\Job;
use App\Repositories\Asimov\Management\Roles\RoleContract;
use Illuminate\Contracts\Bus\SelfHandling;

class DeleteRoleJob extends Job implements SelfHandling
{

    /**
     * @var RoleContract
     */
    private $roles;

    /**
     * @param RoleContract $roles
     */
    function __construct(RoleContract $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->roles->delete(\Route::current()->getParameter('roles'));
    }

}
