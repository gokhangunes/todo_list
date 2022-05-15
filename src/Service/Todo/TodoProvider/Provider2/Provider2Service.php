<?php

namespace App\Service\Todo\TodoProvider\Provider2;

use Symfony\Component\HttpFoundation\Request;
use App\Service\Todo\TodoProvider\ProviderInterface;

class Provider2Service extends AbstractProvider implements ProviderInterface
{
    const TODO_ENDPOIND = 'v2/5d47f235330000623fa3ebf7';
    const PROVIDER_NAME = 'PROVIDER_2';

   public function getAllTodo(): array
    {
        $todoListResponse = $this->provider1Service->request(Request::METHOD_GET, self::TODO_ENDPOIND);

        $todosData =$todoListResponse->toArray();

        $todos['provider'] = self::PROVIDER_NAME;

        foreach ($todosData as  $todo)
        {
            $key = array_key_first($todo);
            $todos['plans'][] = [
                'level' => $todo[$key]['level'],
                'time' => $todo[$key]['estimated_duration'],
                'referenceId' => $key,
            ];
        }

        return $todos;
    }
}