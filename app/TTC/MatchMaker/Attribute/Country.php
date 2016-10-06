<?php

namespace App\TTC\MatchMaker\Attribute;

use App\Exceptions\GeneralException;
use App\TTC\MatchMaker\Operator\Equals;
use App\TTC\MatchMaker\Operator\NotEquals;
use App\TTC\MatchMaker\Operator\Operator;

/**
 * Class Country
 * @package App\TTC\MatchMaker\Attribute
 */
class Country extends Attribute
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
        $operator = \App::make($this->rule->operator,
            [json_decode($this->rule->values, true), $this->profile->geo_country_id]);

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
            return sprintf("from %s", $this->getCountryNameById($values[0]));
        } elseif ($operator instanceof NotEquals) {
            return sprintf("not from %s", $this->getCountryNameById($values[0]));
        }

        return null;
    }

    /**
     * @param $countryId
     * @return null|string
     */
    private function getCountryNameById($countryId)
    {
        $country = \App\TTC\Models\Country::whereId($countryId)->first();

        if (is_null($country)) {
            return null;
        }

        return $country->name;
    }

    public function getUrlQuery()
    {
        $operator = \App::make($this->rule->operator,
            [json_decode($this->rule->values, true), null]);

        return [
            'country' => $operator->getUrlQuery()
        ];
    }
}
