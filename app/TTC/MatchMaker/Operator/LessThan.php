<?php

namespace App\TTC\MatchMaker\Operator;

use Carbon\Carbon;


/**
 * Class LessThan
 * @package App\TTC\MatchMaker\Operator
 */
class LessThan extends Operator
{

    public $name = "Less Than";
    public $symbol = "<";

    /**
     * @return bool
     */
    public function match()
    {
        $expected = Carbon::now()->subYears($this->expected[0]);
        $birthday = Carbon::createFromFormat('Y-m-d', $this->actual);

        return $birthday->gte($expected);
    }

    public function getUrlQuery()
    {
        return [
            '$lte' => $this->expected[0]
        ];
    }
}
