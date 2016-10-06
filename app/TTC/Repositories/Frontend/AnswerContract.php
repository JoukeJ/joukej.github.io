<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile\Survey;
use App\TTC\Models\Survey\Answer;

interface AnswerContract
{
    /**
     * @param array $input
     * @return Answer
     * @throws GeneralException
     */
    public function create(array $input);
}
