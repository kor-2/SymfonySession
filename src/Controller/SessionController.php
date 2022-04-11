<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/session/add", name="add_session")
     * @Route("/session/update/{id}", name="update_session")
     */
    public function add(ManagerRegistry $doctrine, Session $session = null, Request $request): Response
    {
    if (!$session) {
        $session = new Session();
    }

    $entityManager = $doctrine->getManager();
    $form = $this->createForm(SessionType::class, $session);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $session = $form->getData();
        $entityManager->persist($session);
        $entityManager->flush();
        

        return $this->redirectToRoute('list_session');
    }

    return $this->render('session/add.html.twig', [
        'addSession' => $form->createView(),
    ]);
    }

    /**
     * Ajouter des stagiaires
     */

    /**
     * @Route("/session/ajout_sess/{id_sess}/{id_stag}", name="add_sess_stagiaire")
     * 
     * @ParamConverter("session", options={"mapping" = {"id_sess" : "id"}})
     * @ParamConverter("stagiaire", options={"mapping" = {"id_stag" : "id"}})
     */
    public function addStag(Session $session ,ManagerRegistry $doctrine, Stagiaire $stagiaire ): Response
    {
        
        $entityManager = $doctrine->getManager();
        $session->addStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /**
     * Supprimer des stagiaires
     */
    
    /**
     * @Route("/session/del_sess/{id_sess}/{id_stag}", name="del_sess_stagiaire")
     * 
     * @ParamConverter("session", options={"mapping" = {"id_sess" : "id"}})
     * @ParamConverter("stagiaire", options={"mapping" = {"id_stag" : "id"}})
     */
    public function delStag(Session $session ,ManagerRegistry $doctrine, Stagiaire $stagiaire ): Response
    {
        
        $entityManager = $doctrine->getManager();
        $session->removeStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /**
     * @Route("/session/{id}", name="show_session")
     */
    public function show(Session $session, ManagerRegistry $doctrine): Response
    {
        $nonInscrits = $doctrine->getRepository(Stagiaire::class)->getNonInscrits($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
        ]);
    }
}
