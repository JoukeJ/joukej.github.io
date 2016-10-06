<?php namespace App\TTC\Chain\Response;

use App\TTC\Chain\ChainResponse;
use App\TTC\Models\Survey\Entity\Option;
use App\TTC\Models\Survey\Entity\Question\BaseQuestion;

class AnswerResponse extends ChainResponse
{
    /**
     * @var BaseQuestion
     */
    protected $question;

    /**
     * @var string
     */
    protected $jsonAnswer;

    /**
     * @var Option|null
     */
    protected $option = null;

    /**
     * AnswerResponse constructor.
     * @param BaseQuestion $question
     * @param string $jsonAnswer
     * @param Option|null $option
     */
    public function __construct(BaseQuestion $question, $jsonAnswer, $option = null)
    {
        $this->question = $question;
        $this->jsonAnswer = $jsonAnswer;
        $this->option = $option;
    }

    /**
     * @param Option|null $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

    /**
     * @return BaseQuestion
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @return string
     */
    public function getJsonAnswer()
    {
        return $this->jsonAnswer;
    }

    /**
     * @return Option|null
     */
    public function getOption()
    {
        return $this->option;
    }


}
