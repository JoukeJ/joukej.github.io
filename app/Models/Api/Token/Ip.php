<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\Models\Api\Token;

/**
 * App\Models\Api\Token\Ip
 *
 * @property integer $id
 * @property integer $api_token_id
 * @property string $ip
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token\Ip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token\Ip whereApiTokenId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token\Ip whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token\Ip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Token\Ip whereUpdatedAt($value)
 */
class Ip extends \Eloquent
{
    protected $table = 'api_token_ips';

    public $timestamps = true;

    protected $fillable = ['api_token_id', 'ip'];
}
