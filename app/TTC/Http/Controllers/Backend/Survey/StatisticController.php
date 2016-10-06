<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Http\Controllers\Backend\Survey;


use App\Http\Controllers\Controller;
use App\TTC\Models\Survey\Entity\Question\Checkbox;
use App\TTC\Models\Survey\Entity\Question\Open;
use App\TTC\Models\Survey\Entity\Question\Radio;
use App\TTC\Models\Survey\Entity\Question\Text;
use App\TTC\Repositories\Backend\SurveyContract;
use App\TTC\Statistic\Statistic;
use Ddeboer\DataImport\Writer\ExcelWriter;
use Illuminate\Support\Str;

/**
 * Class StatisticController
 * @package App\TTC\Http\Controllers\Backend\Survey
 */
class StatisticController extends Controller
{
    /**
     * @var SurveyContract
     */
    private $surveyRepository;

    /**
     * @param SurveyContract $surveyRepository
     */
    public function __construct(SurveyContract $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($id)
    {
        $survey = $this->surveyRepository->findOrThrowException($id);

        $statistic = new Statistic($survey);

        $name = Str::slug($survey->name);
        $file = new \SplFileObject(storage_path('ttc') . DIRECTORY_SEPARATOR . "statistic-{$name}.xlsx", 'w');

        $writer = new ExcelWriter($file);

        $writer->prepare();

        $writer->writeItem(['Survey ' . $survey->name]);

        $writer->writeItem([
            trans('survey/statistics/index.total_participants'),
            $statistic->totalParticipants(),
        ]);
        $writer->writeItem([
            trans('survey/statistics/index.total_unique_participants'),
            $statistic->totalUniqueParticipants(),
        ]);
        $writer->writeItem([
            trans('survey/statistics/index.total_abandoned'),
            $statistic->totalAbandoned(),
        ]);
        $writer->writeItem([
            trans('survey/statistics/index.total_unique_abandoned'),
            $statistic->totalUniqueAbandoned(),
        ]);
        $writer->writeItem([
            trans('survey/statistics/index.total_progress'),
            $statistic->totalInProgress(),
        ]);


        foreach ($survey->entities as $entity) {
            $statisticEntity = $statistic->getEntity($entity);

            $percentages = $statisticEntity->percentages();

            if (in_array($entity->entity_type, [Open::class, Text::class])) {
                $writer->writeItem([
                    $entity->entity->question,
                    implode("\r\n" . "---" . "\r\n", array_keys($percentages)),
                ]);
            } elseif (in_array($entity->entity_type, [Radio::class, Checkbox::class])) {
                $output = '';
                foreach ($statisticEntity->countPerAnswer() as $answer => $count) {
                    $output .= $count . '/' . round($percentages[$answer], 1) . '% - ' . $answer . "\r\n";
                }
                $writer->writeItem([$entity->entity->question, $output]);
            }
        }

        $writer->finish();

        return \Response::download(storage_path('ttc') . "/statistic-$name.xlsx");
    }
}
