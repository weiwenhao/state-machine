<?php

namespace Weiwenhao\StateMachine\Tests;

use Illuminate\Database\Eloquent\Model;
use \Mockery;
use Weiwenhao\StateMachine\Graph;
use Weiwenhao\StateMachine\StateMachineException;
use Weiwenhao\StateMachine\Tests\Stubs\ExampleGraph;

class GraphTest extends TestCase
{
    protected $model;
    protected $graph;

    /**
     * @throws StateMachineException
     * @throws \ReflectionException
     */
    public function setUp()
    {
        parent::setUp();

        $this->model = $this->getMockForAbstractClass(Model::class);
        $this->model->state = null;

        $this->graph = ExampleGraph::with($this->model);
    }


    /**
     * @throws StateMachineException
     */
    public function testConstruct()
    {
        $graph = new ExampleGraph($this->model);
        $this->assertEquals($this->model, $graph->getObject());
    }

    public function testInit()
    {
        $this->graph->init();
        $this->assertEquals('new', $this->model->state);

        return $this->graph;
    }

    /**
     * @depends testInit
     * @param $graph
     */
    public function testGetState($graph)
    {
        $state = $graph->getState();
        $this->assertEquals('new', $state);
    }

    /**
     * @depends testInit
     * @param $graph
     */
    public function testCan($graph)
    {
        $this->assertTrue($graph->can('fulfill'));
    }

    /**
     * @depends testInit
     * @param $graph
     */
    public function testApply($graph)
    {
        $this->assertTrue($graph->apply('fulfill'));
        $this->assertEquals('fulfilled', $graph->getState());
    }

    /**
     * @throws StateMachineException
     */
    public function testEvent()
    {
        $graph = ExampleGraph::with($this->model);
        $graph->init();

        try {
            $graph->apply('cancel');
        } catch (\Exception $exception) {
            $this->assertEquals('cancelled', $exception->getMessage());
        }
    }
}
