<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Service\DeveloperService;
use App\Service\Todo\TodoService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    /**
     * @Route("/{developer}", name="default.index")
     * @Template()
     */
    public function index(TodoService $todoService, DeveloperService $developerService)
    {

        return [
            'developers' => $developerService->getAllDeveloper(),
            'plans' => $todoService->getPlansByDeveloperId()
        ];
    }

    /**
     * @Route("/developer/{developer}", name="default.developer.todo")
     * @Template()
     */
    public function todo(Developer $developer, TodoService $todoService, DeveloperService $developerService)
    {

        return [
            'developers' => $developerService->getAllDeveloper(),
            'developer' => $todoService->getPlansByDeveloper($developer)
        ];
    }
}
