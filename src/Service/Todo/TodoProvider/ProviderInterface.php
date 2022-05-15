<?php

namespace App\Service\Todo\TodoProvider;

interface ProviderInterface
{
    public function __construct();

    public function getAllTodo(): array;
}