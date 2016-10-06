<?php

namespace App\TTC\Models;

/**
 * App\TTC\Models\Language
 *
 * @property integer $id
 * @property string $name
 * @property string $iso
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Language whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Language whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Language whereIso($value)
 */
class Language extends \Eloquent
{

    protected $table = 'languages';
    public $timestamps = false;
    protected $fillable = array('name', 'iso');
    protected $visible = array('name', 'iso');

}
