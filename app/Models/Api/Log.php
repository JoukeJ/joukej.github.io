<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\Models\Api;

/**
 * App\Models\Api\Log
 *
 * @property integer $id
 * @property string $url
 * @property string $input
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Log whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Log whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Log whereInput($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Api\Log whereUpdatedAt($value)
 */
class Log extends \Eloquent
{
    protected $table = 'api_log';

    public $timestamps = true;

    protected $fillable = ['url', 'input'];
}
