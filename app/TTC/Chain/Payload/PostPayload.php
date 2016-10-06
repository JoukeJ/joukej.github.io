<?php namespace App\TTC\Chain\Payload;

use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;

class PostPayload extends ChainPayload
{
    /**
     * @var bool
     */
    protected $handled = false;

    /**
     * @var AnswerResponse|null
     */
    protected $answerResponse = null;

    /**
     * @var string|string[]
     */
    public $input;

    /**
     * @param Profile $profile
     * @param Survey $survey
     * @param Entity $entity
     * @param $input
     */
    public function __construct(Profile $profile, Survey $survey, Entity $entity, $input)
    {
        parent::__construct($profile, $survey, $entity);

        $this->input = $input;
    }

    /**
     * @return boolean
     */
    public function isHandled()
    {
        return $this->handled;
    }

    /**
     * @param boolean $handled
     */
    public function setHandled($handled)
    {
        $this->handled = $handled;
    }

    /**
     * @return AnswerResponse|null
     */
    public function getAnswerResponse()
    {
        return $this->answerResponse;
    }

    /**
     * @param AnswerResponse|null $answerResponse
     */
    public function setAnswerResponse(AnswerResponse $answerResponse)
    {
        $this->answerResponse = $answerResponse;
    }


}
