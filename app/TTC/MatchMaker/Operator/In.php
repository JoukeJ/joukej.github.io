<?php

namespace App\TTC\MatchMaker\Operator;


/**
 * Class In
 * @package App\TTC\MatchMaker\Operator
 */
class In extends Operator
{

    public $name = "In";
    public $symbol = "IN";

    /**
     * @return bool
     */
    public function match()
    {
        return in_array($this->actual, $this->expected);
    }

    public function getUrlQuery()
    {
        return [
            '$in' => $this->expected
        ];
    }
}
