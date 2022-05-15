<?php
namespace App\Service;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;

class DeveloperService
{
    protected DeveloperRepository $developerRepository;

    public function __construct(DeveloperRepository $developerRepository)
    {
        $this->developerRepository = $developerRepository;
    }

    public function getAllDeveloper()
    {
        return $this->developerRepository->findAll();
    }

    public function getDevelopers()
    {
        $developersData = $this->getAllDeveloper();
        $developerData = [];

        foreach ($developersData as $dev) {
            $labor = ($dev->getLevel()->getTime() * $dev->getLevel()->getAbility()) * Developer::WEEKLY_WORKING_HOURS;
            $dev->setLabor($labor);
            $developerData[$dev->getLevel()->getAbility()][] = $dev;
        }


        asort($developerData);

        return $developerData;
    }
}