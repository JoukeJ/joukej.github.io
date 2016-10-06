<?php namespace App\TTC\Models\Survey\Entity\Question;

use App\TTC\Chain\ChainResponse;
use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Chain\Response\EndOfChain\ErrorResponse;
use App\TTC\Chain\Response\NoActionResponse;

/**
 * App\TTC\Models\Survey\Entity\Question\Image
 *
 * @property integer $id
 * @property string $question
 * @property string $description
 * @property boolean $required
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Image whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Image whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Image whereRequired($value)
 */
class Image extends BaseQuestion
{
    protected $table = 'survey_entity_q_image';

    public $presentView = 'question.image';

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.questions.image.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.questions.image.edit', [
            'entity' => $this
        ]);
    }

    /**
     * @param PostPayload $payload
     * @return ChainResponse
     */
    public function answer(PostPayload $payload)
    {
        $validation = $this->getValidator(\Input::all());
        if ($validation->fails()) {
            return new ErrorResponse($validation->messages()->toArray());
        }

        if ($payload->isHandled() === false) {
            if (($this->required == true || \Input::hasFile('file')) && ($path = $this->handleUploadedImage($payload)) !== false) {
                return new AnswerResponse($this, json_encode($path));
            } else {
                if ($this->required == false) {
                    $payload->setHandled(true);

                    return new NoActionResponse();
                }

                return new ErrorResponse('Cannot process image.');
            }
        } else {
            // If dumbphone..
            return new NoActionResponse();

            // else
            return new \App\TTC\Chain\Response\EndOfChain\RedirectResponse(\Redirect::route('survey', [
                $payload->getProfile()->identifier,
                $payload->getSurvey()->identifier,
                $this->baseEntity->identifier
            ]), $this->baseEntity->id);
        }
    }

    /**
     * @return string
     */
    protected function getValidationRules()
    {
        $rules = [];

        if ($this->required == true) {
            $rules[] = 'required';
        }

        $rules[] = 'image';

        return ['file' => implode("|", $rules)];
    }

    /**
     * @param PostPayload $payload
     * @return bool|string
     */
    private function handleUploadedImage(PostPayload $payload)
    {
        if (\Input::hasFile('file') === false) {
            return false;
        }

        $extension = \Input::file('file')->getClientOriginalExtension();

        $storagePath = $this->getStoragePath(
            $payload->getSurvey()->id,
            $payload->getEntity()->id,
            $payload->getProfile()->id,
            $extension
        );

        try {
            \Input::file('file')->move($storagePath[0], $storagePath[1]);
        } catch (\Exception $e) {
            return false;
        }

        return str_replace(storage_path(), '', $storagePath[2]);
    }

    /**
     * Returns /ttc/uploaded/<survey>/<entity>/<profile>-<sequence_no>.<extension>
     * @param $survey
     * @param $entity
     * @param $profile
     * @param string $extension
     * @return \string[]
     */
    private function getStoragePath($survey, $entity, $profile, $extension)
    {
        $format = 'ttc' . DIRECTORY_SEPARATOR . 'uploaded' . DIRECTORY_SEPARATOR . '%s' . DIRECTORY_SEPARATOR . '%s' . '-';
        $storagePath = storage_path(sprintf($format, $survey, $entity));

        $n = 1;
        while ((\File::exists($storagePath . $profile . '-' . $n))) {
            $n++;
        }

        $filename = $profile . '-' . $n;

        if (empty($extension) === false) {
            $filename = $filename . '.' . $extension;
        }

        return [
            $storagePath,
            $filename,
            $storagePath . $filename
        ];
    }
}
