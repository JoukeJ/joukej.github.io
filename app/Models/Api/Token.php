<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\Models\Api;


/**
 * App\Models\Api\Token
 *
 * @property integer $id
 * @property string $customer
 * @property string $token
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token whereCustomer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token whereToken($value)
 */
class Token extends \Eloquent
{
    protected $table = 'api_tokens';

    public $timestamps = false;

    protected $fillable = ['customer', 'token'];
}
