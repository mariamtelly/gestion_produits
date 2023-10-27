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
    #[Route('/produits/liste')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produits = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste_produits.html.twig', [
            'produits' => $produits,
        ]);
    }

   #[Route('/produits/nouveau')]
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

            return $this->redirectToRoute('/produits/nouveau'); // A modifier
        }

        return $this->render("produit/nouveau_produit.html.twig", ["form" => $form,]);
   }

   #[Route('/produits/{id}')]
    public function show(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return $this->render('produit/produit_non_trouve.html.twig');
        }

        return $this->render('produit/produit_trouve.html.twig', ['produit' => $produit,]);
    }


}
