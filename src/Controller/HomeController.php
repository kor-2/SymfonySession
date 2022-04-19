<?php

namespace App\Controller;

use App\Repository\FormateurRepository;
use App\Repository\FormationRepository;
use App\Repository\StagiaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(StagiaireRepository $sr, FormationRepository $fr, FormateurRepository $formteurRepo): Response
    {
        $stagiaires = $sr->findAll();
        $formations = $fr->findAll();
        $formateurs = $formteurRepo->findAll();

        return $this->render('home/index.html.twig', [
            'stagiaires' => $stagiaires,
            'formations' => $formations,
            'formateurs' => $formateurs,
        ]);
    }
}
