<?php

namespace App\TTC\MatchMaker\MashConnector;


use App\TTC\Models\Survey;

/**
 * Class MashConnectorAbstract
 * @package App\TTC\MatchMaker\Fetch
 */
abstract class MashConnectorAbstract
{
    /**
     * @var URLBuilder
     */
    protected $urlBuilder;

    /**
     * MashConnectorAbstract constructor.
     * @param Survey $survey
     */
    public function __construct(Survey $survey)
    {
        $this->urlBuilder = new URLBuilder($survey);
    }

    /**
     * @return string
     */
    abstract public function get();
}
