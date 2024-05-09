<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Group;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{

    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }


    /**
     * @return QuestionFactory|Factory
     */
    public function configure(): QuestionFactory|Factory
    {
        return $this->afterCreating(function (Group $group) {
            // Create 4 answers associated with this question
            $pointArray = $this->generatePointArray();
            foreach ($pointArray as $point) {
                $data = [
                    'text' => $this->faker->sentence,
                    'difficult_type' => 'hard',
                    'max_point' => $point,
                    'time_to_move_point' => $point > 10 ? $this->faker->numberBetween(20, 40) : null,
                    'min_score' => $point * 5 / 100,
                    'moveable_point_percent' => $point > 10 ? $this->faker->numberBetween(10,20) : null,
                ];
                $group->questions()->create($data);
            }

            $groupQuestions = $group->questions;

            foreach ($groupQuestions as $groupQuestion) {
                $data = [];
                for ($i = 1; $i <= 4; $i++) {
                    $data[] = [
                        'question_id' => $groupQuestion->id,
                        'text' => 'Wrong Answer',
                        'is_correct' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $dataIndex  = random_int(0,3);
                $data[$dataIndex]['is_correct'] = true;
                $data[$dataIndex]['text'] = "Right Answer";

                Answer::insert($data);
            }
        });
    }

    /**
     * @return array
     */
    public function generatePointArray(): array
    {
        $length = rand(4, 8);

        $numbers = [];

        $remainingSum = 100;

        for ($i = 0; $i < $length - 1; $i++) {
            $min = max(1, $remainingSum - (35 * ($length - $i - 1)));
            $max = min(35, $remainingSum - ($length - $i - 1));

            if ($min <= $max) {
                $number = rand($min, $max);
            } else {
                $number = $remainingSum;
            }

            $numbers[] = $number;

            $remainingSum -= $number;
        }

        $numbers[] = $remainingSum;

        shuffle($numbers);

        return $numbers;
    }
}
