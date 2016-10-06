<?php

namespace App\TTC\MatchMaker\Operator;

use App\Exceptions\GeneralException;

/**
 * Class Operator
 * @package App\TTC\MatchMaker\Operator
 */
abstract class Operator
{

    public $symbol;
    /**
     * @var mixed
     */
    protected $expected;
    /**
     * @var mixed
     */
    protected $actual;
    /**
     * @var int
     */
    public $numInput = 1;

    /**
     * @param $expected
     * @param $actual
     */
    public function __construct($expected = null, $actual = null)
    {
        $this->expected = $expected;
        $this->actual = $actual;
    }

    /**
     * @return bool
     */
    abstract public function match();

    /**
     * @return string
     */
    abstract public function getUrlQuery();

}
