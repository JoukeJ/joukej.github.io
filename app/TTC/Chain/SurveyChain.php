<?php namespace App\TTC\Chain;

use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;
use App\TTC\Repositories\Frontend\EntityContract;
use App\TTC\Repositories\Frontend\ProfileSurveyContract;

class SurveyChain
{
    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var Survey
     */
    protected $survey;

    /**
     * @var Entity|null
     */
    protected $entity;

    /**
     * @var Profile\Survey
     */
    protected $profileSurvey;

    /**
     * @var ChainItem[]
     */
    protected $items = [];

    /**
     * @var ChainItem
     */
    protected $tip;

    /**
     * @var EntityContract
     */
    protected $entityRepository;

    /**
     * @var ProfileSurveyContract
     */
    protected $profileSurveyRepository;

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @return Entity|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return Profile\Survey
     */
    public function getProfileSurvey()
    {
        return $this->profileSurvey;
    }

    /**
     * SurveyChain constructor.
     * @param Profile $profile
     * @param Survey $survey
     * @param Entity|null $entity
     */
    public function __construct(Profile $profile, Survey $survey, $entity = null)
    {
        $this->profile = $profile;
        $this->survey = $survey;
        $this->entity = $entity;

        $this->entityRepository = \App::make(EntityContract::class);
        $this->profileSurveyRepository = \App::make(ProfileSurveyContract::class);

        $this->profileSurvey = $this->profileSurveyRepository->findOrCreate([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'progress'
        ]);

        $this->buildChain();
    }

    /**
     * @param ChainPayload $payload
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \App\Exceptions\GeneralException
     */
    public function invoke(ChainPayload $payload)
    {
        return $this->invokeRaw($payload)->getObject();
    }

    /**
     * @param ChainPayload $payload
     * @return Response\EndOfChainResponse
     * @throws \App\Exceptions\GeneralException
     */
    public function invokeRaw(ChainPayload $payload)
    {
        $response = $this->tip->handle($payload);
        $response->fireEvents();

        return $response;
    }

    /**
     * Build the entity chain based on the current survey and entity
     */
    protected function buildChain()
    {
        $entities = $this->entityRepository->getOrderedEntitiesBySurveyId($this->survey->id, $this->entity);

        // Start from the end of chain
        $entities = $entities->reverse();

        $previousEntity = null;
        foreach ($entities as $entity) {
            $previousEntity = $this->addItem(new ChainItem($this, $entity, $previousEntity));
        }

        $this->tip = $previousEntity;
    }

    /**
     * @param ChainItem $item
     * @return ChainItem
     */
    protected function addItem(ChainItem $item)
    {
        $this->items[] = $item;

        return $item;
    }

}
