<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="list_session")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository(Session::class)->findBy([], ['debut' => 'ASC']);

        $jour = new \DateTime();

        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'jour' => $jour,
        ]);
    }

    /**
     * @Route("/session/{id}", name="show_session")
     */
    public function add(Session $session, ManagerRegistry $doctrine): Response
    {
        $nonInscrits = $doctrine->getRepository(Stagiaire::class)->getNonInscrits($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
        ]);
    }
}
