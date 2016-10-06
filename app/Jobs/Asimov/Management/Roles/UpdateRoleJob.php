<?php namespace App\Jobs\Asimov\Management\Roles;

use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\Models\Role;
use App\Repositories\Asimov\Management\Roles\RoleContract;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdateRoleJob extends Job implements SelfHandling
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
     * @throws GeneralException
     * @return Role
     */
    public function handle()
    {
        return $this->roles->update(
            \Input::get('id'),
            \Input::except(['id', 'permissions']),
            \Input::get('permissions')
        );
    }

}
