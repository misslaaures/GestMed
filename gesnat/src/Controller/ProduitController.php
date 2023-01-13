<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="home_produit")
     */

    public function index()
    {
        $em = $this->getDoctrine()->getManager();
      $categories = $em->getRepository(Categorie::class)->findAll();  
      $produits = $em->getRepository(Produit::class)->findAll();
        return $this->render('project/produit.html.twig', array(
          'produits'  => $produits,
          'categories'  => $categories
        ));
    }

     /**
     * @Route("/afficherProduit", name="afficher_produit")
     */
    

    public function afficher_produit(Request $request)
    {
        dump($request);
        // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(produit::class)->find($id);

        // Si Utilisateur trouvé
        if(isset($produit))
        {
          $id=$produit->getId();
          $designation=$produit->getDesignation();
          $prix=$produit->getPrix();
          $qtestock=$produit->getQtestock();
          $stockmini=$produit->getStockmini();
          $categorieid=$produit->getCategorie()->getId();
          $categorietitre=$produit->getCategorie()->getTitre();
        }

        // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
          return new JsonResponse(array('id'=>$id,'designation'=>$designation, 'prix'=>$prix, 'qtestock'=>$qtestock, 'stockmini'=>$stockmini, 'categorieid'=>$categorieid, 'categorietitre'=>$categorietitre));
        }
        
    }

    /**
     * @Route("/supprimerProduit", name="supprimer_produit")
     */

    public function supprimer_produit(Request $request)
    {
      //dump($request);  
      // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);

        $res=false;

       // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
          $em->remove($produit);
            $em->flush();

            $res=true;
            return new JsonResponse(array('res'=>$res));
            $em = $this->getDoctrine()->getManager();
            $produits = $em->getRepository(Produit::class)->findAll();  
            
      
              return $this->render('project/produit.html.twig', array(
                'produits'  => $produits
              ));
            
        }
        
        
    }

    /**
     * @Route("/enregisterproduit", name="enregistrer_produit")
     */

    public function enregistrerAction(Request $request): Response
    {
        // Recuperer variable post
        $produitid=$request->request->get('produitid');
        $designation=$request->request->get('designation');
        $prix=$request->request->get('prix');
        $qtestock=$request->request->get('qtestock');
        $stockmini=$request->request->get('stockmini');
        $categorieid=$request->request->get('type');
        
        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($categorieid);

        if($produitid==null)
        {
            // Création de l'entitité
            $produit = new Produit();
            $produit->setDesignation($designation);
            $produit->setPrix($prix);
            $produit->setQtestock($qtestock);
            $produit->setStockmini($stockmini);
            $produit->setCategorie($categorie);
            $produit->setDatemodif(new \Datetime());

            $em->persist($produit);

            $em->flush();
           
        }
        else
        {
            // On récupère l'annonce $id
            $produit = $em->getRepository(Produit::class)->find($produitid);

            $produit->setDesignation($designation);
            $produit->setPrix($prix);
            $produit->setQtestock($qtestock);
            $produit->setStockmini($stockmini);
            $produit->setCategorie($categorie);
            $produit->setDatemodif(new \Datetime());

            $em->flush();

            // Ajouter un message flash dans la session
          
        }

        // Rediriger vers la même page
        
              return $this->redirectToRoute('home_produit');
    }
    
}
