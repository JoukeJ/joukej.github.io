<?php


namespace App\TTC\Repositories\Backend;


use App\Exceptions\GeneralException;
use App\TTC\Models\Survey\Entity;
use Illuminate\Database\Eloquent\Collection;

interface EntityContract
{

    /**
     * @param $id
     * @param bool|false $withMorph
     * @throws GeneralException
     * @return Entity
     */
    public function findOrThrowException($id, $withMorph = false);

    /**
     * @param $identifier
     * @param bool|false $withMorph
     * @return Entity
     */
    public function findFromIdentifierOrThrowException($identifier, $withMorph = false);

    /**
     * @param $id
     * @throws GeneralException
     * @return Entity\Option
     */
    public function findEntityOptionOrThrowException($id);

    /**
     * If $afterId === 0 then it will make the entity the first in order
     * @param array $input
     * @param int $afterId
     * @throws GeneralException
     * @return Entity
     */
    public function create($input, $afterId = null);

    /**
     * @param $id
     * @param $input
     * @throws GeneralException
     * @return Entity
     */
    public function update($id, $input);

    /**
     * @param $id
     * @throws GeneralException
     * @return bool
     */
    public function delete($id);

    /**
     * @param int $id
     * @param int $afterId
     * @throws GeneralException
     * @return Entity
     */
    public function move($id, $afterId);

    /**
     * @param int $entityId
     * @param string[] $skiplogic
     * @return Entity\Logic\Skip[]|Collection|bool
     */
    public function syncSkiplogic($entityId, $skiplogic);
}
