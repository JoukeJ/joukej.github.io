<?php

namespace App\TTC\MatchMaker\MashConnector;


use App\TTC\Models\Survey;
use Illuminate\Support\Collection;

/**
 * Class URLBuilder
 * @package App\TTC\MatchMaker\Fetch
 */
class URLBuilder
{
    /**
     * @var Survey
     */
    private $survey;

    /**
     * @var Collection
     */
    private $attributes;

    /**
     * ProfileFetch constructor.
     * @param Survey $survey
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;

        $this->attributes = new Collection();

        foreach ($this->survey->matchgroups as $matchgroup) {
            foreach ($matchgroup->rules as $matchrule) {
                $attribute = app($matchrule->attribute, [null, $matchrule]);

                $this->attributes->push($attribute);
            }
        }
    }

    /**
     * @return string
     */
    private function buildUri()
    {
        $queries = [
            'profile' => []
        ];

        foreach ($this->attributes as $attribute) {
            $queries['profile'] = array_merge($attribute->getUrlQuery(), $queries['profile']);
        }

        return '/api/v1/' . urldecode(http_build_query($queries));
    }

    /**
     * @return string
     */
    public function count()
    {
        return $this->buildUri() . '&query_type=count';
    }

    /**
     * @return string
     */
    public function profiles()
    {
        return $this->buildUri();
    }
}
