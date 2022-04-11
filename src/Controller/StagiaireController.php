<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="list_stagiaire")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
            'title' => 'Liste des stagiaires',
        ]);
    }

    /**
     * @Route("/stagiaire/add", name="add_stagiaire")
     * @Route("/stagiaire/update/{id}", name="update_stagiaire")
     */
    public function add(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request): Response
    {
    if (!$stagiaire) {
        $stagiaire = new Stagiaire();
    }

    $entityManager = $doctrine->getManager();
    $form = $this->createForm(StagiaireType::class, $stagiaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $stagiaire = $form->getData();
        $entityManager->persist($stagiaire);
        $entityManager->flush();
        

        return $this->redirectToRoute('list_stagiaire');
    }

    return $this->render('stagiaire/add.html.twig', [
        'addStagiaire' => $form->createView(),
    ]);
    }



    /**
     * @Route("/stagiaire/{id}", name="show_stagiaire")
     */
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

}
