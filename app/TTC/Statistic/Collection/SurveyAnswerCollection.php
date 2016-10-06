<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Statistic\Collection;


use App\TTC\Models\Survey\Answer;
use App\TTC\Models\Survey\Entity\Question\Checkbox;
use App\TTC\Models\Survey\Entity\Question\Open;
use App\TTC\Models\Survey\Entity\Question\Radio;
use App\TTC\Models\Survey\Entity\Question\Text;
use Illuminate\Support\Collection;

class SurveyAnswerCollection extends Collection
{
    public function countPerAnswer()
    {
        $answers = [];

        $this->each(function (Answer $item) use (&$answers) {
            if ($item->entity->entity_type == Text::class || $item->entity->entity_type == Open::class || $item->entity->entity_type == Radio::class) {
                $answer = trim($item->answer, '"');
                if (!isset($answers[$answer])) {
                    $answers[$answer] = 0;
                }

                $answers[$answer]++;
            } elseif ($item->entity->entity_type == Checkbox::class) {
                $checboxes = json_decode($item->answer, true);

                foreach ($checboxes as $checkbox) {
                    $checkbox = (empty($checkbox)) ? trans('survey/statistics/index.empty') : $checkbox;

                    if (!isset($answers[$checkbox])) {
                        $answers[$checkbox] = 0;
                    }

                    $answers[$checkbox]++;
                }
            }
        });

        asort($answers);

        return $answers;
    }

    public function percentages()
    {
        $answers = $this->countPerAnswer();

        $totalCount = 0;

        foreach ($answers as $answer => $count) {
            $totalCount += $count;
        }

        foreach ($answers as $answer => $count) {
            $answers[$answer] = 100 / $totalCount * $answers[$answer];
        }

        return $answers;
    }
}
