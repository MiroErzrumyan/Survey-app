<?php

namespace App\Repositories;

use App\Contracts\AnswerContract;
use App\Models\Answer;

class AnswerRepository implements AnswerContract
{

    public function __construct(protected Answer $model)
    {
    }

    public function getById($answerId,$relations = []): mixed
    {
        return $this->model->with($relations)->findOrFail($answerId);
    }
}
