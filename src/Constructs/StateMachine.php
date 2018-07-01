<?php

namespace Weiwenhao\StateMachine\Constructs;

use Weiwenhao\StateMachine\StateMachineException;

interface StateMachine
{
    public function init();

    /**
     * Can the transition be applied on the underlying object
     *
     * @param string $transition
     *
     * @return bool
     *
     * @throws StateMachineException If transition doesn't exist
     */
    public function can($transition);

    /**
     * Applies the transition on the underlying object
     *
     * @param string $transition Transition to apply
     * @param bool $soft Soft means do nothing if transition can't be applied (no exception thrown)
     *
     * @return bool If the transition has been applied or not (in case of soft apply or rejected pre transition event)
     *
     * @throws StateMachineException If transition can't be applied or doesn't exist
     */
    public function apply($transition, $soft = false);

    /**
     * Returns the current state
     *
     * @return string
     */
    public function getState();

    /**
     * Returns the underlying object
     *
     * @return object
     */
    public function getObject();

    /**
     * Returns the possible transitions
     *
     * @return array
     */
    public function getPossibleTransitions();
}
