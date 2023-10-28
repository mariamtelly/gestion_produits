<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\Type\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    #[Route('/produits', name:'produits')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getManager()->getRepository(Produit::class)->findAll();
    
        return $this->render('produit/all.html.twig', [
            'produits' => $produits,
        ]);
    }

   #[Route('/produits/nouveau', name:'nouveau_produit')]
   public function new(Request $request, PersistenceManagerRegistry $doctrine): Response
   {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produits'); // A modifier
        }

        return $this->render("produit/new.html.twig", ["form" => $form,]);
   }

   #[Route('/produits/afficher/{id}', name:'afficher_produit')]
    public function show(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $produit = $doctrine->getManager()->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return $this->render('produit/notFound.html.twig');
        }

        return $this->render('produit/print.html.twig', ['produit' => $produit,]);
    }

   
    #[Route('/produits/modifier/{id}', name:'modifier_produit')]
    public function update(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $produit = $doctrine->getManager()->getRepository(Produit::class)->find($id);

        if(!$produit){
            return $this->render('produit/notFound.html.twig');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('afficher_produit', ['id' => $produit->getId()]);
        }

        return $this->render("produit/alter.html.twig", ["form" => $form,]);
    }

    #[Route('/produits/supprimer/{id}', name:'supprimer_produit')]
    public function delete(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if(!$produit){
            return $this->render('produit/notFound.html.twig');
        }

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('produits');
    }
}
