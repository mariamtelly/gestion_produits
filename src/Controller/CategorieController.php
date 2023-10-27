<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Type\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


class CategorieController extends AbstractController
{
    #[Route('/categories/liste', name: 'categories')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/liste_categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/nouveau', name:'nouvelle_categorie')]
   public function new(Request $request, PersistenceManagerRegistry $doctrine): Response
   {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('/categories/nouvelle'); // A modifier
        }

        return $this->render("categorie/nouvelle_categorie.html.twig", ["form" => $form,]);
   }

   #[Route('/categories/{id}')]
    public function show(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            return $this->render('categorie/categorie_non_trouve.html.twig');
        }

        return $this->render('categorie/categorie_trouve.html.twig', ['categorie' => $categorie]);
    }

}
