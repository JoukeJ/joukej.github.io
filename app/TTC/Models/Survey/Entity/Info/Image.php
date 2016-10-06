<?php

namespace App\TTC\Models\Survey\Entity\Info;

use App\Exceptions\GeneralException;

/**
 * App\TTC\Models\Survey\Entity\Info\Image
 *
 * @property integer $id
 * @property string $description
 * @property string $path
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Image whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Image wherePath($value)
 * @property string $identifier
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Info\Image whereIdentifier($value)
 */
class Image extends BaseInfo
{

    protected $table = 'survey_entity_i_image';
    public $timestamps = false;
    protected $fillable = array('description', 'path');
    protected $visible = array('description', 'path');

    public $presentView = 'info.image';

    public $files = [
        'path'
    ];

    /**
     * Returns the url this image can be viewed from
     * @return string
     * @throws GeneralException
     */
    public function getUrl()
    {
        return \URL::route('storage.image', [
            $this->identifier
        ]);
    }

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.info.image.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.info.image.edit', [
            'entity' => $this
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderAfterIdOption()
    {
        return view('ttc.backend.surveys.entities.info.image.afterIdOption', [
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
        $rules['path'] = 'required|image';

        return $rules;
    }

    /**
     * @return array
     */
    public function getUpdateRules()
    {
        $rules = parent::getUpdateRules();

        $rules['entity_type.description'] = 'required';
        $rules['path'] = 'image';

        return $rules;
    }

    /**
     *
     */
    public function initNew()
    {
        parent::initNew();

        $this->identifier = str_random(8);
    }


}
