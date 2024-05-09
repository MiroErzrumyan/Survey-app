<?php

namespace App\Contracts;

interface AnswerContract
{

    /**
     * @param $answerId
     * @param $relations
     * @return mixed
     */
    public function getById($answerId, $relations): mixed;
}
