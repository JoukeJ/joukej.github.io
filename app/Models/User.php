<?php namespace App\Models;

use App\Presenters\PresenterTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nqxcode\LuceneSearch\Model\SearchTrait;
use Zizaco\Entrust\Contracts\EntrustUserInterface;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $language
 * @property string $timezone
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.role[] $roles
 * @property-read mixed $presenter
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 */
class User extends \Eloquent implements AuthenticatableContract, CanResetPasswordContract, EntrustUserInterface
{

    use Authenticatable, CanResetPassword, EntrustUserTrait, SoftDeletes, PresenterTrait, SearchTrait;

    protected $table = 'users';

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'language', 'timezone'];

    protected $hidden = ['password', 'remember_token'];

    public static function boot()
    {
        parent::boot();
        self::bootSearchTrait();
    }

    /**
     * @var bool is the user a superuser?
     */
    protected $_superuser = null;

    /**
     * @param $permission string[]|string
     * @param $requireAll bool
     * @return bool
     */
    public function may($permission, $requireAll = false)
    {
        if ($this->isSuperuser()) {
            return true;
        }

        return $this->can($permission, $requireAll);
    }

    /**
     * @return bool
     */
    protected function isSuperuser()
    {
        if ($this->_superuser !== null) {
            return $this->_superuser;
        }

        $this->_superuser = false;

        foreach ($this->roles as $role) {
            foreach ($role->perms as $perm) {
                if ($perm->name === 'superuser') {
                    $this->_superuser = true;
                    break 2;
                }
            }
        }

        return $this->_superuser;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Returns a survey index url based on permissions
     * @return string
     */
    public function getSurveyUrl()
    {
        if ($this->may('management.survey.seeall')) {
            return \URL::route('surveys.index');
        } else {
            return \URL::route('surveys.my_index');
        }
    }
}
