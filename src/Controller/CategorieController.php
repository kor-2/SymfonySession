<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="list_categorie")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('categorie/index.html.twig', [
            'title' => 'Liste des catégories',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categorie/add", name="add_categorie")
     * @Route("/categorie/update/{id}", name="update_categorie")
     */
    public function add(ManagerRegistry $doctrine, Categorie $categorie = null, Request $request): Response
    {
        if (!$categorie) {
            $categorie = new Categorie();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('list_categorie');
        }

        return $this->render('categorie/add.html.twig', [
        'addCategorie' => $form->createView(),
        'editMode' => $categorie->getId(),
    ]);
    }

    /**
     * @Route("/categorie/{id}", name="show_categorie")
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
