<?php

namespace Weiwenhao\StateMachine\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class GraphMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:graph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new graph class';

    protected $type = 'Graph';


    protected function getStub()
    {
        if ($this->option('demo')) {
            return __DIR__.'/stubs/graph-demo.stub';
        }

        return __DIR__.'/stubs/graph.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Graphs';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['demo', 'd', InputOption::VALUE_OPTIONAL, 'Create a new graph demo class'],
        ];
    }
}
