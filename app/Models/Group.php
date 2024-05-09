<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function questions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    /**
     * @return BelongsToMany
     */
    public function selectedGroupAnswers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class,'selected_group_answers')
                    ->withPivot('answer_point');
    }

    /**
     * Get the answers associated with the group.
     */
    public function answers()
    {
        return $this->belongsToMany(Answer::class, 'selected_group_answers')
            ->withPivot('additional_column'); // if you have additional columns in the pivot table
    }

}
