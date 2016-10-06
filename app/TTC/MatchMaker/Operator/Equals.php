<?php

namespace App\TTC\MatchMaker\Operator;


/**
 * Class Equals
 * @package App\TTC\MatchMaker\Operator
 */
class Equals extends Operator
{

    public $name = "Equals";
    public $symbol = "==";

    /**
     * @return bool
     */
    public function match()
    {
        return $this->expected[0] == $this->actual;
    }

    public function getUrlQuery()
    {
        return [
            '$eq' => $this->expected[0]
        ];
    }
}
