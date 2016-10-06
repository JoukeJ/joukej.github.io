<?php namespace App\Models\User;

/**
 * App\Models\User\Emailchange
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $email
 * @property boolean $valid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereValid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\Emailchange whereUpdatedAt($value)
 */
class Emailchange extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'user_emailchanges';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'token', 'email'];

}
