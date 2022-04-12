<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use App\Form\ModuleFormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/module_formation/add", name="add_module_formation")
     * @Route("/module_formation/update/{id}", name="update_module_formation")
     */
    public function add(ManagerRegistry $doctrine, ModuleFormation $module = null, Request $request): Response
    {
        if (!$module) {
            $module = new ModuleFormation();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ModuleFormationType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('list_module_formation');
        }

        return $this->render('module_formation/add.html.twig', [
        'addModule' => $form->createView(),
        'editMode' => $module->getId(),
    ]);
    }
}
