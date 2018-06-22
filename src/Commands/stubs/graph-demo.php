<?php

namespace DummyNamespace;

use Illuminate\Database\Eloquent\Model;
use Weiwenhao\StateMachine\Graph;

class DummyClass extends Graph
{
    protected $states = [
        'cart',
		'new',
        'cancelled',
        'fulfilled'
    ];

    protected $initState = 'cart';

    protected $transitions = [
        'create' => [
            'from' => ['cart'],
            'to' => 'new',
        ],
        'cancel' => [
            'from' => ['new'],
            'to' => 'cancel',
        ],
        'fulfilled' => [
            'from' => ['new'],
            'to' => 'fulfilled'
        ]
    ];

    /**
     * @param $object
     */
    public function onCreate(Model $object)
    {

    }

}
