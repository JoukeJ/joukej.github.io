<?php

namespace App\TTC\MatchMaker\Operator;

/**
 * Class NotEquals
 * @package App\TTC\MatchMaker\Operator
 */
class NotEquals extends Operator
{

    public $name = "Not Equals";
    public $symbol = "!=";

    /**
     * @return bool
     */
    public function match()
    {
        return $this->expected[0] != $this->actual;
    }

    public function getUrlQuery()
    {
        return [
            '$ne' => $this->expected[0]
        ];
    }
}
