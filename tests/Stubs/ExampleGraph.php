<?php

namespace Weiwenhao\StateMachine\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use \Exception;
use Weiwenhao\StateMachine\Graph;

class ExampleGraph extends Graph
{
    protected $states = [
        'new',
        'cancelled',
        'fulfilled'
    ];

    protected $initState = 'new';

    protected $graph = [
        'cancel' => [
            'from' => ['new'],
            'to' => 'cancelled',
        ],
        'fulfill' => [
            'from' => ['new'],
            'to' => 'fulfilled'
        ]
    ];

    /**
     * @param Model $model
     * @throws Exception
     */
    public function onCancel(Model $model)
    {
        throw new Exception('cancelled');
    }

    public function onFulfill(Model $model)
    {
        return 'fulfilled';
    }
}
