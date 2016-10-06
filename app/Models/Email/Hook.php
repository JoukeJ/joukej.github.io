<?php namespace App\Models\Email;

/**
 * App\Models\Email\Hook
 *
 * @property integer $id
 * @property integer $email_id
 * @property boolean $sent
 * @property boolean $bounced
 * @property boolean $opened
 * @property boolean $spam
 * @property boolean $rejected
 * @property boolean $delayed
 * @property boolean $soft_bounced
 * @property boolean $clicked
 * @property boolean $unsubscribed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereEmailId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereSent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereBounced($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereOpened($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereSpam($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereRejected($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereDelayed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereSoftBounced($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereClicked($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereUnsubscribed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email\Hook whereUpdatedAt($value)
 */
class Hook extends \Eloquent
{
    protected $table = 'email_hooks';
}
