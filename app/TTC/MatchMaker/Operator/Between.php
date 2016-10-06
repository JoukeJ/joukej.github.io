<?php

namespace App\TTC\MatchMaker\Operator;

/**
 * Class Between
 * @package App\TTC\MatchMaker\Operator
 */
class Between extends Operator
{

    public $name = "Between";
    public $symbol = "> <";
    public $numInput = 2;

    /**
     * @return bool
     */
    public function match()
    {
        $greater = new GreaterThan([$this->expected[0]], $this->actual);
        $lesser = new LessThan([$this->expected[1]], $this->actual);

        return $greater->match() && $lesser->match();
    }

    public function getUrlQuery()
    {
        return [
            '$gt' => $this->expected[0],
            '$lt' => $this->expected[1]
        ];
    }
}
