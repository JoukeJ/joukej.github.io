<?php

namespace App\TTC\Models\Survey\Entity\Info;

use App\TTC\Common\Helper;

/**
 * App\TTC\Models\Survey\Entity\Info\Video
 *
 * @property integer $id
 * @property string $description
 * @property string $service
 * @property string $url
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Video whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Video whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Video whereService($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Video whereUrl($value)
 */
class Video extends BaseInfo
{
    protected $table = 'survey_entity_i_video';
    public $timestamps = false;
    protected $fillable = array('description', 'service', 'url');
    protected $visible = array('description', 'service', 'url');

    public $presentView = 'info.video';

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.info.video.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.info.video.edit', [
            'entity' => $this
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderAfterIdOption()
    {
        return view('ttc.backend.surveys.entities.info.video.afterIdOption', [
            'entity' => $this
        ]);
    }

    /**
     * @return array
     */
    public function getStoreRules()
    {
        $rules = parent::getStoreRules();

        $rules['entity_type.description'] = 'required';

        $services = config('ttc.survey.entity.video.services');

        $rules['entity_type.service'] = 'required|in:' . implode(",", array_keys($services));
        $rules['entity_type.url'] = 'required';

        return $rules;
    }

    /**
     * @return array
     */
    public function getUpdateRules()
    {
        $rules = parent::getUpdateRules();

        $rules['entity_type.description'] = 'required';

        $services = config('ttc.survey.entity.video.services');

        $rules['entity_type.service'] = 'required|in:' . implode(",", array_keys($services));
        $rules['entity_type.url'] = 'required';

        return $rules;
    }

    public function initNew()
    {

    }

    public function afterFill()
    {
        parent::afterFill();

        $video_id = Helper::parseYoutubeUrl($this->url);
        if($video_id !== null){
            $this->url = $video_id;
        }
    }
}
