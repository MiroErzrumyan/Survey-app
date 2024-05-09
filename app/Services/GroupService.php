<?php

namespace App\Services;

use App\Contracts\AnswerContract;

class GroupService
{

    /**
     * @param AnswerContract $answerRepository
     */
    public function __construct(protected AnswerContract $answerRepository)
    {
    }


    /**
     * @param $requestData
     * @return int[]
     */
    public function makeAnswerData($requestData): array
    {
        $data = [
            'answer_point' => 0
        ];
        $answer = $this->answerRepository->getById($requestData['answer_id'],'question');
        if ($answer->is_correct){

            $point = $answer->question->max_point;
            $minPoint = $answer->question->min_score;
            $percent = $answer->question->moveable_point_percent;
            $decreased_times = $requestData['decreased_times'];
            if ($decreased_times) {
                if (floor(100 / $percent) == $decreased_times){
                    $data['answer_point'] = $minPoint;
                }else {
                    $answer_point = $point - ($point * ($decreased_times * $percent) / 100);
                    $data['answer_point'] = $answer_point;

                }
            }else {
                $data['answer_point'] = $point;
            }
        }

        return $data;
    }
}
