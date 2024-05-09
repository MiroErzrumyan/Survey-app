<?php

namespace App\Contracts;

interface GroupContract
{
    /**
     * @param $relations
     * @return mixed
     */
    public function getRandomGroupWithRelations($relations): mixed;

    /**
     * @param $groupId
     * @param $relations
     * @return mixed
     */
    public function getByIdWithRelations($groupId, $relations): mixed;


    /**
     * @param $groupId
     * @param $answerId
     * @param $data
     * @return mixed
     */
    public function storeGroupAnswers($groupId, $answerId, $data);

    /**
     * @param $groupId
     * @return mixed
     */
    public function deleteGroupAnswers($groupId): mixed;
}
