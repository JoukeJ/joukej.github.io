<?php
/**
 * Created by Luuk Holleman
 * Date: 17/06/15
 */

namespace App\TTC\Models\Survey;


/**
 * App\TTC\Models\Survey\Log
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $model
 * @property string $original
 * @property string $changed
 * @property string $action
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereOriginal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereChanged($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Log whereUpdatedAt($value)
 */
class Log extends \Eloquent
{
    protected $table = 'survey_log';
    public $timestamps = true;
    protected $fillable = array('user_id', 'model', 'original', 'changed', 'action');
}
