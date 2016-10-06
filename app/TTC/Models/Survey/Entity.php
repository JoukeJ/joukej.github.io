<?php

namespace App\TTC\Models\Survey;

use App\Exceptions\GeneralException;
use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\ChainResponse;
use App\TTC\Chain\HandlesChain;
use Illuminate\Support\Arr;

/**
 * App\TTC\Models\Survey\Entity
 *
 * @property integer $id
 * @property integer $survey_id
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $order
 * @property-read \App\TTC\Models\Survey\Entity\BaseEntity $entity
 * @property-read \App\TTC\Models\Survey $survey
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity whereSurveyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity whereEntityType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity whereOrder($value)
 * @property string $identifier
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity whereIdentifier($value)
 */
class Entity extends \Eloquent implements HandlesChain
{

    protected $table = 'survey_entities';
    public $timestamps = false;
    protected $fillable = [];
    protected $visible = ['identifier', 'order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey()
    {
        return $this->belongsTo('App\TTC\Models\Survey');
    }

    /**
     * @param ChainPayload $payload
     * @return ChainResponse
     * @throws GeneralException
     */
    public function handleChain(ChainPayload $payload)
    {
        return $this->entity->handleChain($payload);
    }


    /**
     * Returns the short type (e.g. q_open, l_skip, i_image etc..)
     * @return string
     */
    public function getShortType()
    {
        $types = array_flip(\Config::get('ttc.entity.types'));

        return Arr::get($types, $this->entity_type);
    }

    /**
     * Returns if this entities subentity implements $interface
     * @param $interface
     * @return bool
     */
    public function isImplementationOf($interface)
    {
        return with(new \ReflectionClass($this->entity_type))->implementsInterface($interface);
    }

    /**
     * Returns of the subentity is a subclass of $class
     * @param $class
     * @return bool
     */
    public function isSubclassOf($class)
    {
        return with(new \ReflectionClass($this->entity_type))->isSubclassOf($class);
    }

}
