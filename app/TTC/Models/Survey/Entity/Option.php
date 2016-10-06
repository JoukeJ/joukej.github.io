<?php

namespace App\TTC\Models\Survey\Entity;

use App\TTC\Models\Survey\Entity\Logic\Skip;

/**
 * App\TTC\Models\Survey\Entity\Option
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
 * @property string $name
 * @property string $value
 * @property-read \ $entity
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Option whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Option whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Option whereEntityType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Option whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Option whereValue($value)
 * @property-read Skip $skipLogic
 */
class Option extends \Eloquent
{

    protected $table = 'survey_entity_options';
    public $timestamps = false;
    protected $fillable = array('entity_id', 'entity_type', 'name', 'value');
    protected $visible = array('entity_id', 'entity_type', 'name', 'value');

    public function entity()
    {
        return $this->morphTo();
    }

    public function skipLogic()
    {
        return $this->hasOne(Skip::class);
    }

    public function getSkipLogicId()
    {
        $skip = $this->skipLogic;

        if ($skip !== null) {

            return $skip->entity_id;
        }

        return null;
    }
}
