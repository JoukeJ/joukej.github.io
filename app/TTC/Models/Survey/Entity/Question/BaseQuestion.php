<?php namespace App\TTC\Models\Survey\Entity\Question;

use App\Exceptions\GeneralException;
use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\Payload\GetPayload;
use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Chain\Response\EndOfChain\ErrorResponse;
use App\TTC\Chain\Response\EndOfChain\RedirectResponse;
use App\TTC\Chain\Response\EndOfChain\RenderResponse;
use App\TTC\Models\Survey\Entity\BaseEntity;
use App\TTC\Tags\Entity\CanBeAnswered;
use App\TTC\Tags\Entity\CanBePresented;
use Illuminate\Support\Facades\Validator;

abstract class BaseQuestion extends BaseEntity implements CanBeAnswered, CanBePresented
{
    public $timestamps = false;
    protected $fillable = array('question', 'description', 'required');
    protected $visible = array('question', 'description');

    // Questions can be answered
    public $canBeAnswered = true;

    protected $mainViewFolder = 'questions';

    /**
     * @param PostPayload $payload
     * @return ErrorResponse|AnswerResponse
     * @throws GeneralException
     * @internal param PostPayload $payload
     */
    public function answer(PostPayload $payload)
    {
        $validator = $this->getValidator(['answer' => $payload->input]);
        if ($validator->fails()) {
            return new ErrorResponse($validator->messages()->toArray());
        }

        $jsonAnswer = json_encode($payload->input);
        $answerResponse = new AnswerResponse($this, $jsonAnswer);

        return $answerResponse;
    }

    /**
     * @return string
     */
    public function renderAfterIdOption()
    {
        return view('ttc.backend.surveys.entities.questions.afterIdOption', [
            'entity' => $this
        ]);
    }

    /**
     * @return mixed
     */
    public function renderSkipOption()
    {
        return $this->question;
    }

    /**
     * @return array
     */
    public function getUpdateRules()
    {
        $rules = parent::getUpdateRules();

        $rules['entity_type.question'] = 'required|max:128';

        return $rules;
    }

    /**
     * @return array
     */
    public function getStoreRules()
    {
        $rules = parent::getStoreRules();

        $rules['entity_type.question'] = 'required|max:128';

        return $rules;
    }

    /**
     * @param ChainPayload $payload
     * @return \App\TTC\Chain\ChainResponse
     * @throws GeneralException
     */
    public function handleChain(ChainPayload $payload)
    {
        if ($payload instanceof PostPayload) {
            return $this->handlePostPayload($payload);
        } elseif ($payload instanceof GetPayload) {
            return new RenderResponse($this->present($payload->getProfile(), $payload->getSurvey()));
        }

        return parent::handleChain($payload);
    }

    /**
     * @param PostPayload $payload
     * @return AnswerResponse|ErrorResponse|RedirectResponse
     * @throws GeneralException
     */
    protected function handlePostPayload(PostPayload $payload)
    {
        if ($payload->isHandled()) {
            $profileIdentifier = $payload->getProfile()->identifier;

            return new RedirectResponse(\Redirect::to($this->getRouteUrl($profileIdentifier)), $this->baseEntity->id);
        } else {
            return $this->answer($payload);
        }
    }

    /**
     * @return array
     */
    protected function getValidationRules()
    {
        return [];
    }

    /**
     * @param $input
     * @return array|bool
     */
    protected function getValidator($input)
    {
        return Validator::make($input, $this->getValidationRules());
    }
}
