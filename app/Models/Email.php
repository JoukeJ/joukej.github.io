<?php namespace App\Models;

/**
 * App\Models\Email
 *
 * @property integer $id
 * @property string $identifier
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $status
 * @property string $sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereIdentifier($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereUpdatedAt($value)
 * @property string $reject_reason
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Email whereRejectReason($value)
 */
class Email extends \Eloquent
{

    /**
     * @var array
     */
    protected $fillable = ['identifier', 'from', 'to', 'subject', 'body', 'status'];

}
