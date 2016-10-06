<?php

namespace App\TTC\Jobs\Frontend\Entity;

use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\TTC\Chain\Payload\GetPayload;
use App\TTC\Chain\SurveyChain;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;
use App\TTC\Models\Survey\Entity\BaseEntity;
use App\TTC\Repositories\Frontend\EntityContract;
use App\TTC\Repositories\Frontend\ProfileContract;
use App\TTC\Repositories\Frontend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class RequestEntityJob extends Job implements SelfHandling
{
    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var Survey
     */
    public $survey;

    /**
     * @var Entity
     */
    public $entity;

    /**
     * @var BaseEntity
     */
    public $entityType;

    /**
     * @param ProfileContract $profiles
     * @param SurveyContract $surveys
     * @param EntityContract $entities
     */
    public function __construct(ProfileContract $profiles, SurveyContract $surveys, EntityContract $entities)
    {
        $profileIdentifier = \Route::current()->getParameter('profileId');
        $surveyIdentifier = \Route::current()->getParameter('surveyId');
        $entityIdentifier = \Route::current()->getParameter('entityId');

        $this->profile = $profiles->findByIdentifierOrThrowException($profileIdentifier);
        $this->survey = $surveys->findByIdentifierOrThrowException($surveyIdentifier);
        $this->entity = $entities->findByIdentifierOrThrowException($entityIdentifier);

        $this->entityType = $this->entity->entity;
    }

    /**
     * @throws GeneralException
     * @return \View|\Redirect
     */
    public function handle()
    {
        $chain = new SurveyChain($this->profile, $this->survey, $this->entity);

        return $chain->invoke(new GetPayload($this->profile, $this->survey, $this->entity));
    }
}
