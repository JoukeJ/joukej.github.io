<?php

namespace App\TTC\MatchMaker\Operator;

use Carbon\Carbon;

/**
 * Class GreaterThan
 * @package App\TTC\MatchMaker\Operator
 */
class GreaterThan extends Operator
{
    public $name = "Greater Than";
    public $symbol = ">";

    /**
     * @return bool
     */
    public function match()
    {
        $expected = Carbon::now()->subYears($this->expected[0]);
        $birthday = Carbon::createFromFormat('Y-m-d', $this->actual);

        return $birthday->lte($expected);
    }

    public function getUrlQuery()
    {
        return [
            '$gt' => $this->expected[0]
        ];
    }
}
