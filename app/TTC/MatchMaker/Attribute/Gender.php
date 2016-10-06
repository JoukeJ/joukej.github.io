<?php

namespace App\TTC\MatchMaker\Attribute;

use App\Exceptions\GeneralException;
use App\TTC\MatchMaker\Operator\Equals;
use App\TTC\MatchMaker\Operator\NotEquals;
use App\TTC\MatchMaker\Operator\Operator;

/**
 * Class Gender
 * @package App\TTC\MatchMaker\Attribute
 */
class Gender extends Attribute
{
    /**
     * @var array
     */
    public $operators = [
        Equals::class,
        NotEquals::class
    ];

    /**
     * @return bool
     * @throws GeneralException
     */
    public function match()
    {
        $operator = \App::make($this->rule->operator, [json_decode($this->rule->values, true), $this->profile->gender]);

        return $operator->match();
    }

    /**
     * @param Operator $operator
     * @param array $values
     * @return string
     */
    public function formatRuleString(Operator $operator, array $values)
    {
        if ($operator instanceof Equals) {
            return sprintf("of the gender %s", $values[0]);
        } elseif ($operator instanceof NotEquals) {
            return sprintf("not of the gender %s", $values[0]);
        }

        return null;
    }

    public function getUrlQuery()
    {
        $operator = \App::make($this->rule->operator,
            [json_decode($this->rule->values, true), null]);

        return [
            'sex' => $operator->getUrlQuery()
        ];
    }
}
