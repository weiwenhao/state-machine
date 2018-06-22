<?php

namespace Weiwenhao\StateMachine;

trait StateMachineHelper
{
    /**
     * @return bool
     * @throws StateMachineException
     */
    public function init()
    {
        $this->setState($this->initState);

        return true;
    }

    /**
     * @param string $transition
     * @return bool
     * @throws StateMachineException
     */
    public function can($transition)
    {
        if (!isset($this->graph[$transition])) {
            throw new StateMachineException("Transition {$transition} dose not exist");
        }

        if (!in_array($this->getState(), $this->graph[$transition]['from'])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $transition
     * @param bool $soft
     * @return bool
     * @throws StateMachineException
     */
    public function apply($transition, $soft = false)
    {
        if (!$this->can($transition)) {
            if ($soft) {
                return false;
            }

            throw new StateMachineException("Transition {$transition} cannot be applied");
        }

        $this->setState($this->graph[$transition]['to']);

        // 后置回调
        $functionName = camel_case('on_' . $transition);
        method_exists($this, $functionName) && call_user_func_array([$this, $functionName], [$this->object]);

        return true;
    }


    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->object->{$this->key};
    }

    public function getObject()
    {
        return $this->object;
    }

    /**
     * {@inheritDoc}
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * 获取能够使用的transitions
     */
    public function getPossibleTransitions()
    {
        return array_filter(array_keys($this->graph), [$this, 'can']);
    }

    /**
     * Set a new state to the object
     *
     * @param string $state
     * @throws StateMachineException
     */
    protected function setState($state)
    {
        if (!in_array($state, $this->states)) {
            throw new StateMachineException("Cannot set the state to {$state},because it is not defined in the \$this->states.");
        }

        $this->object->{$this->key} = $state;
    }
}
