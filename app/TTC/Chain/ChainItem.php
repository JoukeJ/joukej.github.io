<?php namespace App\TTC\Chain;

use App\Exceptions\GeneralException;
use App\TTC\Chain\Payload\GetPayload;
use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Chain\Response\EndOfChain\RedirectResponse;
use App\TTC\Chain\Response\EndOfChainResponse;
use App\TTC\Chain\Response\NoActionResponse;
use App\TTC\Events\Frontend\Survey\SurveyWasCompletedEvent;
use App\TTC\Jobs\Frontend\Answer\AnswerQuestionJob;
use App\TTC\Models\Survey\Entity;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ChainItem
{
    use DispatchesJobs;

    /**
     * @var SurveyChain
     */
    protected $surveyChain;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var ChainItem|null
     */
    protected $next;

    /**
     * @return SurveyChain
     */
    public function getSurveyChain()
    {
        return $this->surveyChain;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * ChainItem constructor.
     * @param SurveyChain $surveyChain
     * @param Entity $entity
     * @param ChainItem|null $next
     */
    public function __construct(SurveyChain $surveyChain, Entity $entity, $next)
    {
        $this->surveyChain = $surveyChain;
        $this->entity = $entity;
        $this->next = $next;
    }

    /**
     * @param GetPayload|PostPayload|ChainPayload $payload
     * @return EndOfChainResponse
     * @throws GeneralException
     */
    public function handle(ChainPayload $payload)
    {
        $response = $this->entity->handleChain($payload);
        $response->setChainItem($this);

        if ($response instanceof EndOfChainResponse) {

            if ($response instanceof RedirectResponse) {
                if ($response->getToEntityId() === null) {
                    \Event::fire(new SurveyWasCompletedEvent($this->getSurveyChain()->getProfileSurvey()));
                }
            }

            return $response;
        } elseif ($response instanceof AnswerResponse) { // Question got answered

            $this->dispatch(app(AnswerQuestionJob::class, [$response]));

            $payload->setHandled(true);
            $payload->setAnswerResponse($response);

        } elseif ($response instanceof NoActionResponse) {
            // Ok, no action needed. Continue with next item.
        }

        if ($this->next === null) {
            $response = new RedirectResponse(\Redirect::to(\URL::route('survey.complete', [
                $payload->getProfile()->identifier,
                $payload->getSurvey()->identifier
            ])), null);

            $response->setChainItem($this);

            \Event::fire(new SurveyWasCompletedEvent($this->getSurveyChain()->getProfileSurvey()));

            return $response;
        }

        return $this->next->handle($payload);
    }

}
