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
    #[Route('/produits/liste', name:'produits')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getManager()->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste_produits.html.twig', [
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

        return $this->render("produit/nouveau_produit.html.twig", ["form" => $form,]);
   }

   #[Route('/produits/afficher/{id}', name:'afficher_produit_avec_id')]
    public function show(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $produit = $doctrine->getManager()->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return $this->render('produit/produit_non_trouve.html.twig');
        }

        return $this->render('produit/produit_trouve.html.twig', ['produit' => $produit,]);
    }

   
    #[Route('/produits/modifier/{id}', name:'modifier_produit_avec_id')]
    public function update(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $produit = $doctrine->getManager()->getRepository(Produit::class)->find($id);

        if(!$produit){
            return $this->render('produit/produit_non_trouve.html.twig');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('afficher_produit_avec_id', ['id' => $produit->getId()]);
        }

        return $this->render("produit/modifier_produit.html.twig", ["form" => $form,]);
    }
}
