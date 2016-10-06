<?php namespace Test\TTC\Backend\Entity;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Route;
use Test\TTC\BaseTTCTest;

class JobTest extends BaseTTCTest
{
    use WithoutMiddleware, DispatchesJobs;

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testUpdateRadioWithSkiplogic()
    {
        $entity = $this->createEntityRadioQuestion();

        $input = [
            'entity' => [
                'survey_id' => $entity->survey_id
            ],
            'entity_type' => [
                'question' => 'Bliep',
                'description' => 'Blaap',
            ],
            'type_options' => [
                'a' => 'Henk',
                'b' => 'Penk',
                'c' => 'Rank',
                'd' => 'Menk'
            ],
            'afterId' => 0
        ];

        $skip = [
            'entity_id' => $entity->id,
            'skip' => [
                'y' => $entity->id,
                'o' => $entity->id,
                'l' => $entity->id,
                '0' => $entity->id,
            ]
        ];

        \Input::replace($input);


        $route = \Mockery::mock('Illuminate\Routing\Route');
        $route->shouldReceive('getParameter')->andReturn($entity->id);

        Route::shouldReceive('current')->once()->andReturn($route);

        $this->dispatch(app(\App\TTC\Jobs\Backend\Entity\UpdateEntityJob::class));

        $this->dispatch(app(\App\TTC\Jobs\Backend\Entity\SyncSkipLogicJob::class, [$skip]));
    }
}
