<?php

namespace App\TTC\MatchMaker\Attribute;

use App\TTC\MatchMaker\Operator\Between;
use App\TTC\MatchMaker\Operator\GreaterThan;
use App\TTC\MatchMaker\Operator\LessThan;
use App\TTC\MatchMaker\Operator\Operator;

class Age extends Attribute
{
    /**
     * @var array
     */
    public $operators = [
        GreaterThan::class,
        LessThan::class,
        Between::class
    ];

    /**
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function match()
    {
        if($this->profile->birthday == null)
            return false;

        $operator = \App::make($this->rule->operator,
            [json_decode($this->rule->values, true), $this->profile->birthday]);

        return $operator->match();
    }

    /**
     * @param Operator $operator
     * @param array $values
     * @return string
     */
    public function formatRuleString(Operator $operator, array $values)
    {
        if ($operator instanceof GreaterThan) {
            return sprintf("older than %s years old", $values[0]);
        } elseif ($operator instanceof LessThan) {
            return sprintf("younger than %s years old", $values[0]);
        } elseif ($operator instanceof Between) {
            return sprintf("between %s and %s years old", $values[0], $values[1]);
        }

        return null;
    }

    public function getUrlQuery()
    {
        $operator = \App::make($this->rule->operator,
            [json_decode($this->rule->values, true), null]);

        return [
            'age' => $operator->getUrlQuery()
        ];
    }
}
