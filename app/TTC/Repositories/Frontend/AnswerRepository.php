<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile\Survey;
use App\TTC\Models\Survey\Answer;

class AnswerRepository implements AnswerContract
{
    /**
     * @param array $input
     * @return Answer
     * @throws GeneralException
     */
    public function create(array $input)
    {
        try {
            return Answer::create($input);
        } catch (\Exception $e) {
            throw new GeneralException('Could not create answer with given data');
        }
    }
}
