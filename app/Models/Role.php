<?php


namespace App\Models;


use Zizaco\Entrust\EntrustRole;

/**
 * App\Models\Role
 *
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.permission[] $perms
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereUpdatedAt($value)
 */
class Role extends EntrustRole
{
    public $fillable = ['name', 'display_name', 'description'];

    /**
     * Checks whether this role as a certain permission
     * @param $permission int|Permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $checkId = $permission;
        if (is_object($permission)) {
            $checkId = $permission->id;
        }

        foreach ($this->perms as $perm) {
            if ($perm->id == $checkId) {
                return true;
            }
        }

        return false;
    }

}
