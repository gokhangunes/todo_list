<?php

namespace App\Service\Todo\TodoProvider\Provider1;

use Symfony\Component\HttpFoundation\Request;
use App\Service\Todo\TodoProvider\ProviderInterface;

class Provider1Service extends AbstractProvider implements ProviderInterface
{
    const TODO_ENDPOIND = 'v2/5d47f24c330000623fa3ebfa';
    const PROVIDER_NAME = 'PROVIDER_1';

   public function getAllTodo(): array
    {
        $todoListResponse = $this->provider1Service->request(Request::METHOD_GET, self::TODO_ENDPOIND);

        $todosData =$todoListResponse->toArray();

        $todos['provider'] = self::PROVIDER_NAME;

        foreach ($todosData as $todo)
        {
            $todos['plans'][] = [
                'level' => $todo['zorluk'],
                'time' => $todo['sure'],
                'referenceId' => $todo['id'],
            ];
        }

        return $todos;
    }
}