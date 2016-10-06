<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Statistic\Collection;


use Illuminate\Support\Collection;

class ProfileSurveyCollection extends Collection
{
    public function uniqueCount($column)
    {
        $uniques = [];

        $this->each(function ($item) use (&$uniques, $column) {
            if (!in_array($item->$column, $uniques)) {
                $uniques[] = $item->$column;
            }
        });

        return count($uniques);
    }
}
