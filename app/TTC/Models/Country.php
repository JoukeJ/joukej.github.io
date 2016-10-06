<?php

namespace App\TTC\Models;

/**
 * Class Country
 *
 * @package App\TTC\Models
 * @property integer $id
 * @property string $iso
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Country whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Country whereIso($value)
 */
class Country extends \Eloquent
{

    protected $table = 'countries';
    public $timestamps = false;
    protected $fillable = array('name', 'prefix', 'iso');
    protected $visible = array('id', 'iso');

}
