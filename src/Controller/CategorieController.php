<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\Type\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


class CategorieController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getManager()->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/all.html.twig', [
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

            return $this->redirectToRoute('categories'); // A modifier
        }

        return $this->render("categorie/new.html.twig", ["form" => $form,]);
   }

   #[Route('/categories/afficher/{id}', name:'afficher_categorie')]
    public function show(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $categorie =$doctrine->getManager()->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            return $this->render('categorie/notFound.html.twig');
        }

        return $this->render('categorie/print.html.twig', ['categorie' => $categorie]);
    }

    #[Route('/categories/modifier/{id}', name:'modifier_categorie')]
    public function update(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $categorie = $doctrine->getManager()->getRepository(Categorie::class)->find($id);

        if(!$categorie){
            return $this->render('categorie/notFound.html.twig');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('afficher_categorie', ['id' => $categorie->getId()]);
        }

        return $this->render("categorie/alter.html.twig", ["form" => $form,]);
    }

    #[Route('/categories/supprimer/{id}', name:'supprimer_categorie')]
    public function delete(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {

        $entityManager = $doctrine->getManager();

        $defaultCategorie = $entityManager->getRepository(Categorie::class)->findOneBy(['nom' => 'Default']); // cas null à considérer
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        if(!$categorie){
            return $this->render('categorie/notFound.html.twig');
        }

        $produits = $entityManager->getRepository(Produit::class)->findBy(['categorie' => $categorie]);
        foreach ($produits as $produit) {
            $produit->setCategorie($defaultCategorie);
            $entityManager->persist($produit);
        }
        
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('categories');
    }

}
