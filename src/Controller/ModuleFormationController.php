<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleFormationController extends AbstractController
{
    /**
     * @Route("/module_formation", name="list_module_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $modules = $doctrine->getRepository(ModuleFormation::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('module_formation/index.html.twig', [
            'modules' => $modules,
        ]);
    }
}
