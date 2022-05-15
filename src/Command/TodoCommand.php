<?php

namespace App\Command;

use App\Service\Todo\TodoService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Output\OutputInterface;

class TodoCommand extends Command
{
    use LockableTrait;

    public static $defaultName = 'todo:fetch';

    protected TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        parent::__construct();
        $this->todoService = $todoService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Welcome to todo console. if you wanna see console skills write to "--help" to console')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $output->writeln("This command is already running in another process.");

            return 0;
        }

        $payForFailedPlans = $this->todoService->fetchAll();
        dd($payForFailedPlans);
        $this->release();

        if ('$payForFailedPlans') {
            return 0;
        }

        return 1;
    }
}
