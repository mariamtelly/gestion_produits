<?php

namespace App\Controller;

use App\Repository\ArticleCategorieRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\Type\ProduitType;
use App\Entity\Article;
use App\Entity\ArticleCategorie;
use App\Form\Type\ArticleCategorieType;
use App\Form\Type\ArticleType;
use DateTime;



class GlobalController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, ArticleRepository $articleRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $produitsParCategorie = [];

        foreach ($categories as $categorie) {
            $produits = $produitRepository->findProductsByCategorie($categorie, 3);
            $produitsParCategorie[$categorie->getNom()] = $produits;
        }   
        
        $produitsPopulaires = $produitRepository->findMostViewed(5);

        $produitsRandoms = $produitRepository->findRandomProducts(3);

        $produitsPlusVus = $produitRepository->findMostViewed(3);

        $articlesRecents = $articleRepository->findRecentArticles(3);

        return $this->render('index.html.twig', [
            'categories' => $categories,
            'produitsParCategorie' => $produitsParCategorie,
            'produitsPopulaires' => $produitsPopulaires,
            'produitsRandoms' => $produitsRandoms,
            'produitsPlusVus' => $produitsPlusVus,
            'articlesRecents' => $articlesRecents,
        ]);
    }

    #[Route('/produits', name:'produits')]
    public function shopGrid(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {    
        $categories = $categorieRepository->findAll();
        $produits = $produitRepository->findAll(); 

        return $this->render('shop-grid.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
        ]);
    }

    #[Route('/produits/{categorieName}', name:'produits_par_categorie')]
    public function showCategorie(Request $request, CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
    {
        $categorie = $categorieRepository->findOneBy(['nom' => $request->get('categorieName')]);

        return $this->render('products-by-category.html.twig', [
            'categorieName' => $request->get('categorieName'),
            'produits' => $produitRepository->findProductsByCategorie($categorie),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/produits/show/{produitName}', name: 'produit')]
    public function showProduit(Request $request, CategorieRepository $categorieRepository, ProduitRepository $produitRepository, PersistenceManagerRegistry $doctrine): Response
    {
        $produit = $produitRepository->findOneBy(['nom' => $request->get('produitName')]);
        $produit->incrementNombresDeVues();
        
        $doctrine->getManager()->flush($produit);

        $categories = $categorieRepository->findAll();

        
        return $this->render('product-details.html.twig', [
            'produit' => $produit, 
            'categories' => $categories,
        ]);
    }

    #[Route('/panier', name: 'panier')]
    public function showCart(CategorieRepository $categorieRepository, Request $request, ProduitRepository $produitRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $panier = $request->getSession()->get('panier', []);

        $panierBonFormat = [];

        foreach ($panier as $produitId => $qte)
        {
            $produit = $produitRepository->find($produitId);

            if($produit)
            {
                $panierBonFormat[] = [
                    'produit' => $produit,
                    'qte' => $qte,
                ];
            }
        }

        return $this->render('cart.html.twig', [
            'categories' => $categories,
            'panier' => $panierBonFormat,
        ]);
    }

    #[Route('/ajouter/{produitName}', name: 'ajouter-produit-panier')]
    public function addToCart(Request $request, ProduitRepository $produitRepository): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        $produit = $produitRepository->findOneBy(['nom' => $request->get('produitName')]);

        if(is_array($panier) && array_key_exists($produit->getId(), $panier))
        {
            $panier[$produit->getId()] += 1;
        }
        else
        {
            $panier[$produit->getId()] = 1;
        }

        
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }

    #[Route('/facturation', name: 'facturation')]
    public function showCheckout(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('checkout.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/blog', name: 'blog')]
    public function showBlog(CategorieRepository $categorieRepository, ArticleCategorieRepository $articleCategorieRepository, ArticleRepository $articleRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $articleCategories = $articleCategorieRepository->findAll();
        $articlesRecents = $articleRepository->findRecentArticles(3);

        return $this->render('blog-single-sidebar.html.twig', [
            'categories' => $categories,
            'articleCategories' => $articleCategories,
            'articlesRecents' => $articlesRecents,
        ]);
    }


    #[Route('/contact', name: 'contact')]
    public function contactPage(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('contact.html.twig', [
            'categories' => $categories,
        ]);
    }

    /* ---------------------------------------------------------------------*/
    /** fonctions utilitaires */

   
    #[Route('/produits-categories-articles/listes', name: 'listes_produits_categories_et_articles')]
    public function listesProduitsCategoriesEtArticles(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, ArticleCategorieRepository $articleCategorieRepository, ArticleRepository $articleRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieRepository->findAll();
        $articleCategories = $articleCategorieRepository->findAll();
        $articles = $articleRepository->findAll();

        return $this->render('liste-produits-categories-articles.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'articles' => $articles,
            'articleCategories' => $articleCategories,
        ]);
    }
 /* ------------- Produits ------------- */
  
    #[Route('/produits/nouveau')]
   public function nouveauProduit(Request $request, PersistenceManagerRegistry $doctrine): Response
   {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $produit->setImageName($produit->getImageFile()->getFileName());

            $dateActuelle = new DateTime();

            $produit->setDateCreation($dateActuelle);
            $produit->setDateMiseAJour($dateActuelle);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('listes_produits_categories_et_articles'); // A modifier
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
   }

   #[Route('/produits/modifier/{id}')]
    public function updateProduit(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $produit = $doctrine->getManager()->getRepository(Produit::class)->find($id);

        if(!$produit){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $dateActuelle = new DateTime();
            $produit->setDateMiseAJour($dateActuelle);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
    }
 
    #[Route('/produits/supprimer/{id}')]
    public function deleteProduit(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if(!$produit){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('listes_produits_categories_et_articles');
    }
    /**------- Fin --------- */

/* ------------- Categories de Produits ------------- */
  
    #[Route('/categories/nouveau')]
   public function nouvelleCategorie(Request $request, PersistenceManagerRegistry $doctrine): Response
   {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('listes_produits_categories_et_articles'); // A modifier
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
   }

   #[Route('/categories/modifier/{id}')]
    public function updateCategorie(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $categorie = $doctrine->getManager()->getRepository(Categorie::class)->find($id);

        if(!$categorie){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
    }

    #[Route('/categories/supprimer/{id}')]
    public function deleteCategorie(PersistenceManagerRegistry $doctrine, int $id): Response
    {

        $entityManager = $doctrine->getManager();

        $defaultCategorie = $entityManager->getRepository(Categorie::class)->findOneBy(['nom' => 'Catégorie par défaut']); 
        
        if(!$defaultCategorie){
            $defaultCategorie = new Categorie();
            $defaultCategorie->setNom("Catégorie par défaut");
            $defaultCategorie->setDescription("Les produits de cette catégorie ne sont pas classés!");
            $entityManager->persist($defaultCategorie);
        }

        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        if(!$categorie){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $produits = $entityManager->getRepository(Produit::class)->findBy(['categorie' => $categorie]);
        foreach ($produits as $produit) {
            $produit->setCategorie($defaultCategorie);
            $entityManager->persist($produit);
        }
        
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('listes_produits_categories_et_articles');
    }
    /**------- Fin --------- */

    /* ------------- Articles ------------- */
  
    #[Route('/articles/nouveau')]
    public function nouvelArticle(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
 
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $article = $form->getData();
            $dateActuelle = new DateTime();
 
            $article->setDatePublication($dateActuelle);
            $article->setDateMiseAJour($dateActuelle);
 
            $entityManager = $doctrine->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
 
            return $this->redirectToRoute('listes_produits_categories_et_articles'); // A modifier
        }
 
        return $this->render("nouveau.html.twig", ["form" => $form,]);
    }


   #[Route('/articles/modifier/{id}')]
    public function updateArticle(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $article = $doctrine->getManager()->getRepository(Article::class)->find($id);

        if(!$article){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
    }

    #[Route('/articles/supprimer/{id}')]
    public function deleteArticle(PersistenceManagerRegistry $doctrine, int $id): Response
    {

        $entityManager = $doctrine->getManager();

        $article = $entityManager->getRepository(Article::class)->find($id); 
    

        if(!$article){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('listes_produits_categories_et_articles');
    }
    /**------- Fin --------- */

    /* ------------- Categories d'Articles ------------- */

    #[Route('/articles-categories/nouveau')]
   public function nouvelleCategorieArticle(Request $request, PersistenceManagerRegistry $doctrine): Response
   {
        $articleCategorie = new ArticleCategorie();
        $form = $this->createForm(ArticleCategorieType::class, $articleCategorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $articleCategorie = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($articleCategorie);
            $entityManager->flush();

            return $this->redirectToRoute('listes_produits_categories_et_articles'); // A modifier
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
   }

   #[Route('/articles-categories/modifier/{id}')]
    public function updateArticleCategorie(PersistenceManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $articleCategorie = $doctrine->getManager()->getRepository(ArticleCategorie::class)->find($id);

        if(!$articleCategorie){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $form = $this->createForm(ArticleCategorieType::class, $articleCategorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        return $this->render("nouveau.html.twig", ["form" => $form,]);
    }

    #[Route('/articles-categories/supprimer/{id}')]
    public function deleteArticleCategorie(PersistenceManagerRegistry $doctrine, int $id): Response
    {

        $entityManager = $doctrine->getManager();

        $defaultArticleCategorie = $entityManager->getRepository(ArticleCategorie::class)->findOneBy(['nom' => 'Catégorie par défaut']); 
        
        if(!$defaultArticleCategorie){
            $defaultArticleCategorie = new ArticleCategorie();
            $defaultArticleCategorie->setNom("Catégorie par défaut");
            $defaultArticleCategorie->setDescription("Les articles de cette catégorie ne sont pas classés!");
            $entityManager->persist($defaultArticleCategorie);
        }

        $articleCategorie = $entityManager->getRepository(ArticleCategorie::class)->find($id);

        if(!$articleCategorie){
            return $this->redirectToRoute('listes_produits_categories_et_articles');
        }

        $articles = $entityManager->getRepository(Article::class)->findBy(['articleCategorie' => $articleCategorie]);
        foreach ($articles as $article) {
            $article->setCategorie($defaultArticleCategorie);
            $entityManager->persist($article);
        }
        
        $entityManager->remove($articleCategorie);
        $entityManager->flush();

        return $this->redirectToRoute('listes_produits_categories_et_articles');
    }
    /**------- Fin --------- */
}
