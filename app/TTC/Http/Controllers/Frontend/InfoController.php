<?php
/**
 * Created by Luuk Holleman
 * Date: 14/07/15
 */

namespace App\TTC\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\TTC\Repositories\Backend\EntityContract;

class InfoController extends Controller
{
    private $entityRepository;

    /**
     * InfoController constructor.
     * @param $entityRepository
     */
    public function __construct(EntityContract $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function image($entityId)
    {
        $entity = $this->entityRepository->findOrThrowException($entityId);

        return \Response::make(\File::get(storage_path($entity->entity->path)), 200,
            ['Content-type' => \File::mimeType(storage_path($entity->entity->path))]);
    }
}
