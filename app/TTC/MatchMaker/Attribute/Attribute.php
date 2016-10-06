<?php

namespace App\TTC\MatchMaker\Attribute;

use App\TTC\MatchMaker\Operator\Operator;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey\Matchrule;

/**
 * Class Attribute
 * @package App\TTC\MatchMaker\Attribute
 */
abstract class Attribute
{

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var Matchrule
     */
    protected $rule;

    /**
     * @param Profile $profile
     * @param Matchrule $rule
     */
    public function __construct(Profile $profile = null, Matchrule $rule = null)
    {
        $this->profile = $profile;
        $this->rule = $rule;
    }

    /**
     * @var array
     */
    public $operators = [];

    /**
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    abstract public function match();

    /**
     * @param Operator $operator
     * @param array $values
     * @return string
     */
    abstract public function formatRuleString(Operator $operator, array $values);

    abstract public function getUrlQuery();
}
