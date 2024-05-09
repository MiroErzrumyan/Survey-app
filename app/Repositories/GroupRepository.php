<?php

namespace App\Repositories;

use App\Contracts\GroupContract;
use App\Models\Group;

class GroupRepository implements GroupContract
{

    public function __construct(protected Group $model)
    {
    }


    /**
     * @param $groupId
     * @param array $relations
     * @return mixed
     */
    public function getRandomGroupWithRelations($relations = []): mixed
    {

        return $this->model->with($relations)->inRandomOrder()->first();
    }


    /**
     * @param $groupId
     * @param $relations
     * @return mixed
     */
    public function getByIdWithRelations($groupId, $relations = []): mixed
    {
        return $this->model->with($relations)->findOrFail($groupId);

    }


    /**
     * @param $groupId
     * @param $answerId
     * @param $data
     * @return mixed
     */
    public function storeGroupAnswers($groupId, $answerId, $data): mixed
    {
        return $this->model->findOrFail($groupId)->answers()->attach($answerId,$data);
    }

    /**
     * @param $groupId
     * @return mixed
     */
    public function deleteGroupAnswers($groupId): mixed
    {
        return $this->model->findOrFail($groupId)->answers()->detach();
    }
}
