<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="list_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $formations = $doctrine->getRepository(Formation::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }
    /**
     * @Route("/formation/add", name="add_formation")
     * @Route("/formation/update/{id}", name="update_formation")
     */
    public function add(ManagerRegistry $doctrine, Formation $formation = null, Request $request): Response
    {
    if (!$formation) {
        $formation = new Formation();
    }

    $entityManager = $doctrine->getManager();
    $form = $this->createForm(FormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $formation = $form->getData();
        $entityManager->persist($formation);
        $entityManager->flush();
        

        return $this->redirectToRoute('list_formation');
    }

    return $this->render('formation/add.html.twig', [
        'addFormation' => $form->createView(),
    ]);
    }

    /**
     * @Route("/formation/{id}", name="show_formation")
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }
}
