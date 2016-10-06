<?php

namespace App\TTC\Models\Survey\Entity\Logic;

use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\EndOfChain\RedirectResponse;
use App\TTC\Chain\Response\NoActionResponse;
use App\TTC\Exceptions\Chain\InvalidPayloadException;

/**
 * App\TTC\Models\Survey\Entity\Logic\Skip
 *
 * @property integer $id
 * @property integer $option_id
 * @property integer $entity_id
 * @property-read \App\TTC\Models\Survey\Entity\Option $option
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Logic\Skip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Logic\Skip whereOptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Logic\Skip whereEntityId($value)
 * @property-read \App\TTC\Models\Survey\Entity $entity
 */
class Skip extends BaseLogic
{
    protected $table = 'survey_entity_l_skip';
    public $timestamps = false;
    protected $fillable = array('option_id', 'entity_id');
    protected $visible = array('option_id', 'entity_id');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity()
    {
        return $this->belongsTo('App\TTC\Models\Survey\Entity', 'entity_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo('App\TTC\Models\Survey\Entity\Option', 'option_id');
    }

    /**
     * @param ChainPayload $payload
     * @return \App\TTC\Chain\ChainResponse
     * @throws \App\Exceptions\GeneralException
     */
    public function handleChain(ChainPayload $payload)
    {
        if ($payload instanceof PostPayload) {
            if ($payload->isHandled()) {
                $option = $payload->getAnswerResponse()->getOption();
                if ($option !== null && $option->id === $this->option_id) {

                    if ($this->entity_id === null) {
                        return new RedirectResponse(\Redirect::to(\URL::route('survey.complete', [
                            $payload->getProfile()->identifier,
                            $payload->getSurvey()->identifier
                        ])), null);
                    } else {
                        return new RedirectResponse(\Redirect::route('survey', [
                            $payload->getProfile()->identifier,
                            $this->entity->entity->baseEntity->survey->identifier,
                            $this->entity->entity->baseEntity->identifier
                        ]), $this->entity->entity->baseEntity->id);
                    }

                }

                return new NoActionResponse(); // This skiplogic is not relevant
            }

            throw new InvalidPayloadException("Skiplogic cannot function without an answer.");
        }

        return parent::handleChain($payload);
    }
}
