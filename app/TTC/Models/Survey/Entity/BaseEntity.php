<?php namespace App\TTC\Models\Survey\Entity;

use App\Exceptions\GeneralException;
use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\ChainResponse;
use App\TTC\Chain\HandlesChain;
use App\TTC\Common\Helper;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity\Logic\Skip;
use App\TTC\Tags\Entity\CanBePresented;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use URL;

abstract class BaseEntity extends \Eloquent implements HandlesChain
{
    /**
     * This type must have options to function (e.g. Radio, Checkbox)
     * @var bool
     */
    public $mustHaveOptions = false;

    /**
     * If this entity type can be rendered (in the frontend).
     * @var bool
     */
    public $canBePresented = true;

    /**
     * Can the entity type be answered?
     * @var bool
     */
    public $canBeAnswered = false;

    /**
     * Location of this class of entities views
     * @var null
     */
    protected $mainViewFolder = null;

    /**
     * These columns contain file paths.
     * @var array
     */
    public $files = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function baseEntity()
    {
        return $this->morphOne('App\TTC\Models\Survey\Entity', 'entity');
    }

    /**
     * Returns the short type (e.g. q_open, l_skip, i_image etc..)
     * @return string
     */
    public function getShortType()
    {
        $types = array_flip(\Config::get('ttc.entity.types'));

        return Arr::get($types, static::class);
    }

    /**
     * This returns a summery of the entity that can be used in the survey overview page.
     * @param $number
     * @return string
     */
    public function renderSummery($number)
    {
        $class = strtolower(class_basename($this));
        $view = 'ttc.backend.surveys.entities.' . $this->mainViewFolder . '.' . $class . '.summery';

        return view($view, [
            'number' => $number,
            'entity' => $this
        ]);
    }

    /**
     * This method returns a view in which we can create a new entity of this type.
     * @return string
     */
    public function renderCreateForm($survey)
    {
        return sprintf('Create form of %s (%s)', $this->id, static::class);
    }

    /**
     * Here we return a view that can edit the existing entity.
     * @return string
     */
    public function renderEditForm()
    {
        return sprintf('Edit form of %s (%s)', $this->baseEntity->id, static::class);
    }

    /**
     * @return string
     */
    public function renderAfterIdOption()
    {
        return sprintf('<option value="%s">%s</option>', [$this->baseEntity->id, $this->baseEntity->id]);
    }

    /**
     * @param Profile $profile
     * @param Survey $survey
     * @return View
     * @throws GeneralException
     */
    public function present(Profile $profile, Survey $survey)
    {
        if ($this instanceof CanBePresented) {

            return Helper::getFrontendDeviceView($this->getPresentView(), [
                'entity' => $this,
                'profile' => $profile,
                'survey' => $survey
            ]);
        }

        throw new GeneralException("This entitytype cannot be presented.");
    }

    /**
     * @return string
     */
    public function getPresentView()
    {
        return 'survey.entity.' . $this->presentView;
    }

    /**
     * @param $profile
     * @throws GeneralException
     * @return string
     */
    public function getRouteUrl($profile)
    {
        if ($profile instanceof Profile) {
            $profile = $profile->identifier;
        }

        return URL::route('survey.post', [
            $profile,
            $this->baseEntity->survey->identifier,
            $this->baseEntity->identifier
        ]);
    }

    /**
     * @return array
     */
    public function getUpdateRules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getStoreRules()
    {
        return [];
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Collection|Entity[]
     */
    public function getSkipOptions()
    {
        $baseEntity = $this->baseEntity;
        $survey = $baseEntity->survey;

        return $survey->entities()->where('order', '>', $baseEntity->order)->where('entity_type', '!=',
            Skip::class)->get();
    }

    /**
     * @param ChainPayload $payload
     * @return ChainResponse
     * @throws GeneralException
     */
    public function handleChain(ChainPayload $payload)
    {
        throw new GeneralException("Not yet implemented.");
    }

    /**
     * @throws GeneralException
     * @return string
     */
    public function getFrontendUrl()
    {
        throw new GeneralException("No yet implemented.");
    }

    /**
     * Gets called when a new instance of this type is created.
     */
    public function initNew()
    {
        //
    }

    public function afterFill()
    {

    }
}
