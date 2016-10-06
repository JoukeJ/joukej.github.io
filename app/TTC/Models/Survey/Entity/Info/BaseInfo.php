<?php namespace App\TTC\Models\Survey\Entity\Info;

use App\Exceptions\GeneralException;
use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\Payload\GetPayload;
use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Chain\Response\EndOfChain\RedirectResponse;
use App\TTC\Chain\Response\EndOfChain\RenderResponse;
use App\TTC\Chain\Response\NoActionResponse;
use App\TTC\Common\Helper;
use App\TTC\Exceptions\Chain\InvalidPayloadException;
use App\TTC\Models\Survey\Entity\BaseEntity;
use App\TTC\Tags\Entity\CanBePresented;

/**
 * App\TTC\Models\Survey\Entity\Info\BaseInfo
 *
 */
class BaseInfo extends BaseEntity implements CanBePresented
{

    protected $mainViewFolder = 'info';

    /**
     * @param ChainPayload $payload
     * @return \App\TTC\Chain\ChainResponse
     * @throws GeneralException
     */
    public function handleChain(ChainPayload $payload)
    {
        if ($payload instanceof PostPayload) {
            if ($payload->isHandled() === false) {
                return $this->post($payload);
            }

            return new RedirectResponse(\Redirect::route('survey', [
                    $payload->getProfile()->identifier,
                    $payload->getSurvey()->identifier,
                    $this->baseEntity->identifier
                ]
            ), $this->baseEntity->id);
        } elseif ($payload instanceof GetPayload) {

            // do not show info entities on feature phones
            if (Helper::getDeviceType() == 'feature') {
                return new NoActionResponse();
            }

            return new RenderResponse($this->present($payload->getProfile(), $payload->getSurvey()));
        }

        return parent::handleChain($payload);
    }

    public function post(PostPayload $payload)
    {
        $payload->setHandled(true);

        return new NoActionResponse();

    }

    /**
     * @return mixed
     */
    public function renderSkipOption()
    {
        return $this->description;
    }
}
