<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    /**
     * @Route("/formateur", name="list_formateur")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $formateurs = $doctrine->getRepository(Formateur::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
        ]);
    }

    /**
     * @Route("/formateur/add", name="add_formateur")
     * @Route("/formateur/update/{id}", name="update_formateur")
     */
    public function add(ManagerRegistry $doctrine, Formateur $formateur = null, Request $request): Response
    {
    if (!$formateur) {
        $formateur = new Formateur();
    }

    $entityManager = $doctrine->getManager();
    $form = $this->createForm(FormateurType::class, $formateur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $formateur = $form->getData();
        $entityManager->persist($formateur);
        $entityManager->flush();
        

        return $this->redirectToRoute('list_formateur');
    }

    return $this->render('formateur/add.html.twig', [
        'addFormateur' => $form->createView(),
    ]);
    }

    /**
     * @Route("/formateur/{id}", name="show_formateur")
     */
    public function show(Formateur $formateur): Response
    {
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur,
        ]);
    }
}
