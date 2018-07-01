<?php

namespace Weiwenhao\StateMachine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Weiwenhao\StateMachine\Constructs\StateMachine as StateMachineConstruct;

abstract class Graph implements StateMachineConstruct
{
    use StateMachineHelper;

    protected $object;

    protected $states = [];

    protected $transitions = [];

    protected $initState;

    protected $key = 'state';

    /**
     * Transition constructor.
     * @param Model $object
     * @throws StateMachineException
     */
    public function __construct(Model $object = null)
    {
        if (!is_null($object)) {
            $this->setObject($object);
        }
    }


    /**
     * @param Model $object
     * @return Graph
     * @throws StateMachineException
     */
    public static function with(Model $object)
    {
        $graph = new static;
        $graph->setObject($object);

        return $graph;
    }

    /**
     * @param Model $object
     * @throws StateMachineException
     */
    public function setObject(Model $object)
    {
        $this->object = $object;
    }
}
