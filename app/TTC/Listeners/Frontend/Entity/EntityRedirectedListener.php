<?php namespace App\TTC\Listeners\Frontend\Entity;

use App\TTC\Events\Frontend\Entity\EntityRedirectedEvent;
use App\TTC\Repositories\Frontend\AnswerContract;
use App\TTC\Repositories\Frontend\ProfileSurveyContract;

class EntityRedirectedListener
{
    /**
     * @var AnswerContract
     */
    protected $profileSurveys;

    /**
     * EntityRedirectedListener constructor.
     * @param ProfileSurveyContract $profileSurveys
     */
    public function __construct(ProfileSurveyContract $profileSurveys)
    {
        $this->profileSurveys = $profileSurveys;
    }

    /**
     * @param EntityRedirectedEvent $event
     */
    public function handle(EntityRedirectedEvent $event)
    {
        $this->profileSurveys->update($event->redirectResponse->getChainItem()->getSurveyChain()->getProfileSurvey()->id,
            [
                'entity_id' => $event->redirectResponse->getToEntityId()
            ]);
    }
}
