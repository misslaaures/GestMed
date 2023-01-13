<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Entree;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EntreeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class EntreeController extends AbstractController
{
    /**
     * @Route("/entree", name="home_entree")
     */

    public function index()
    {
      $em = $this->getDoctrine()->getManager();
      $entrees = $em->getRepository(Entree::class)->findAll();  
      $produits = $em->getRepository(Produit::class)->findAll();
      

        return $this->render('project/entree.html.twig', array(
          'entrees'  => $entrees,
          'produits' => $produits
        ));
    }

  

    /**
     * @Route("/enregisterentree", name="enregistrer_entree")
     */

    public function enregistrerAction(Request $request): Response
    {
      $id=$request->request->get('type');
      $qteentree=$request->request->get('qteentree');
      $description=$request->request->get('description');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);

        $qtestock=$produit->getQtestock();
        $qtestock=$qtestock+$qteentree;

            // Création de l'entitité
            $entree = new Entree();
            $entree->setProduit($produit);
            $entree->setQteentree($qteentree);
            $entree->setDescription($description);
            $entree->setDatemodif(new \Datetime());
            $produit->setQtestock($qtestock);

            
            $em->persist($entree);

            // Étape 2 : On « flush » tout ce qui a été persisté avant
            $em->flush();


        // Rediriger vers la même page

        return $this->redirectToRoute('home_entree');
    }

}
