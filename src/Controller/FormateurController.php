<?php

namespace App\Controller;

use App\Entity\Formateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/formateur/{id}", name="show_formateur")
     */
    public function show(Formateur $formateur): Response
    {
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur,
        ]);
    }
}
