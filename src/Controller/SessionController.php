<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use App\Entity\Programme;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\ModuleFormationRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="list_session")
     */
    public function index(ManagerRegistry $doctrine, SessionRepository $sr): Response
    {
        $sessEnCour = $sr->enCour();
        $sessFini = $sr->finSess();
        $sessAVenir = $sr->aVenirSess();

        return $this->render('session/index.html.twig', [
            'sessEnCour' => $sessEnCour,
            'sessFini' => $sessFini,
            'sessAVenir' => $sessAVenir,
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
     * Ajouter des stagiaires.
     */

    /**
     * @Route("/session/ajout_sess/{id_sess}/{id_stag}", name="add_sess_stagiaire")
     *
     * @ParamConverter("session", options={"mapping" = {"id_sess" : "id"}})
     * @ParamConverter("stagiaire", options={"mapping" = {"id_stag" : "id"}})
     */
    public function addStag(Session $session, ManagerRegistry $doctrine, Stagiaire $stagiaire): Response
    {
        $entityManager = $doctrine->getManager();
        $session->addStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /**
     * Supprimer des stagiaires.
     */

    /**
     * @Route("/session/del_sess/{id_sess}/{id_stag}", name="del_sess_stagiaire")
     *
     * @ParamConverter("session", options={"mapping" = {"id_sess" : "id"}})
     * @ParamConverter("stagiaire", options={"mapping" = {"id_stag" : "id"}})
     */
    public function delStag(Session $session, ManagerRegistry $doctrine, Stagiaire $stagiaire): Response
    {
        $entityManager = $doctrine->getManager();
        $session->removeStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /**
     * Supprimer des modules.
     */

    /**
     * @Route("/session/del_module/{id_sess}/{id_mod}", name="del_sess_module")
     *
     * @ParamConverter("session", options={"mapping" = {"id_sess" : "id"}})
     * @ParamConverter("programme", options={"mapping" = {"id_mod" : "id"}})
     */
    public function delModule(Session $session, ManagerRegistry $doctrine, Programme $programme): Response
    {
        $entityManager = $doctrine->getManager();
        $session->removeProgramme($programme);
        $entityManager->remove($programme);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', [
            'id' => $session->getId(),
    ]);
    }

    /**
     * Ajouter des modules.
     */

    /**
     * @Route("/session/add_module/{id_sess}/{id_mod}", name="add_sess_module")
     *
     * @ParamConverter("session", options={"mapping" = {"id_sess" : "id"}})
     * @ParamConverter("module", options={"mapping" = {"id_mod" : "id"}})
     */
    public function addModule(Session $session, ManagerRegistry $doctrine, Request $request, ModuleFormation $module): Response
    {
        $entityManager = $doctrine->getManager();

        $programme = new Programme();

        $nbJour = $request->request->get('nbJour');
        $programme->setNbJourModule($nbJour);
        $programme->setModuleFormation($module);
        $programme->setSession($session);
        $entityManager->persist($programme);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId(), 'nbjour' => $nbJour]);
    }

    /**
     * @Route("/session/{id}", name="show_session")
     */
    public function show(Session $session, StagiaireRepository $sr, ModuleFormationRepository $mfr): Response
    {
        $nonInscrits = $sr->getNonInscrits($session->getId());
        $nonProg = $mfr->getNonProg($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
            'nonProg' => $nonProg,
        ]);
    }
}
