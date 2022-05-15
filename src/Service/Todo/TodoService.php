<?php

namespace App\Service\Todo;

use App\Entity\Developer;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use App\Service\DeveloperService;
use App\Service\Todo\TodoProvider\ProviderInterface;
use App\Service\Todo\TodoProvider\Provider1\Provider1Service;
use App\Service\Todo\TodoProvider\Provider2\Provider2Service;

class TodoService
{
    protected TodoRepository $todoRepository;

    protected DeveloperService $developerService;

    public function __construct(TodoRepository $todoRepository, DeveloperService $developerService)
    {
        $this->todoRepository = $todoRepository;
        $this->developerService = $developerService;
    }

    /**
     * @return bool
     */
    public function fetchAll(): bool
    {
        try {

            foreach ($this->getAllProvider() as $provider) {

                $providerData = $this->getTodoByProvider($provider);

                foreach ($providerData['plans'] as $todoData) {
                    $this->saveAndUpdate($providerData['provider'], $todoData);
                }
            }

            return true;
        } catch (\Throwable $e) {
        }

        return false;
    }

    /**
     * @param string $provider
     * @param array  $todoData
     *
     * @return Todo
     */
    public function saveAndUpdate(string $provider, array $todoData): Todo
    {
        $todo = $this->todoRepository->findByProviderAndReferenceId($provider, $todoData['referenceId']);

        if (empty($todo)) {
            $todo = new Todo();
        }

        $todo->setProvider($provider);
        $todo->setLevel($todoData['level']);
        $todo->setProviderReferenceId($todoData['referenceId']);
        $todo->setTime($todoData['time']);

        $this->todoRepository->add($todo, true);

        return $todo;
    }

    /**
     * @param ProviderInterface $provider
     *
     * @return array
     */
    public function getTodoByProvider(ProviderInterface $provider)
    {
        return $provider->getAllTodo();
    }

    /**
     * @return array
     */
    public function getAllProvider(): array
    {
        return [
            new Provider1Service(),
            new Provider2Service(),
        ];
    }

    public function getPlans(): array
    {
        $todosData = $this->todoRepository->findAll();

        $todoLevels = [];
        foreach ($todosData as $todo) {
            $labor = $todo->getTime() * $todo->getLevel();
            $todo->setLabor($labor);
            $todoLevels[$todo->getLevel()][] = $todo;
        }

        asort($todoLevels);
        $week = 1;
        $developers = $this->developerService->getDevelopers();

        foreach ($todoLevels as $level => $todos) {
            /** @var Todo $todo */
            foreach ($todos as $todo) {
                $status = $this->calculate($level, $todo, $developers, $week);
                if ($status !== true) {
                    $week++;

                    $developers = $this->developerService->getDevelopers();

                    $this->calculate($level, $todo, $developers, $week);
                }
            }
        }

        return $developers;
    }

    public function calculate(int $level, Todo &$todo, array &$developers, int $week)
    {
        if ($todo->getLabor() === 0) {
            return true;
        }

        for ($i = $level; $i > 0; $i--) {
            if (empty($developers[$i][0])) {
                return $this->calculate($i-1, $todo, $developers, $week);
            }

            /** @var Developer $developer */
            $developer = $developers[$i][0];

            if ($developer->getLabor() === 0) {
                return $this->calculate($i-1, $todo, $developers, $week);
            }

            if ($developer->getLabor() > $todo->getLabor()) {
                $effor = $todo->getLabor();

                $developer->setLabor($developer->getLabor()-$todo->getLabor());
                $todo->setLabor(0);

                $developers[$i][0] = $developer;

                $developer->addPlans($week, [
                    'effor' => $effor,
                    'todo' => $todo,
                ]);

                return true;
            }

            if ($todo->getLabor() > $developer->getLabor()) {
                $effor = $developer->getLabor();

                $todo->setLabor($todo->getLabor() - $developer->getLabor());
                $developer->setLabor(0);

                $developer->addPlans($week, [
                    'effor' => $effor,
                    'todo' => $todo,
                ]);

                $developers[$i][0] = $developer;
            }

            if ($todo->getLabor() >= 0) {
                return true;
            }

            return $this->calculate($i-1, $todo, $developers, $week);
        }

        return false;
    }

    public function getPlansByDeveloper(Developer $developer)
    {

        $developers = $this->getPlans();

        foreach ($developers as $developerData) {
            if ($developerData[0]->getId() === $developer->getId()) {
                return $developerData[0];
            }
        }

    }
}