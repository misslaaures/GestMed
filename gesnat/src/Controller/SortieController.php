<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Sortie;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SortieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="home_sortie")
     */

    public function index()
    {
      $em = $this->getDoctrine()->getManager();
      $sorties = $em->getRepository(Sortie::class)->findAll();  
      $produits = $em->getRepository(Produit::class)->findAll();
      

        return $this->render('project/sortie.html.twig', array(
          'sorties'  => $sorties,
          'produits' => $produits

        ));
      }

    /**
     * @Route("/enregistersortie", name="enregistrer_sortie")
     */

    public function enregistrerAction(Request $request): Response
    {
      $id=$request->request->get('type');
      $qtesortie=$request->request->get('qtesortie');
      $description=$request->request->get('description');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
        $qtestock=$produit->getQtestock();
           
        if ($qtestock<$qtesortie){
            
        } else {
          $qtestock=$qtestock-$qtesortie;

          // Création de l'entitité
          $sortie = new Sortie();
          $sortie->setProduit($produit);
          $sortie->setQtesortie($qtesortie);
          $sortie->setDescription($description);
          $sortie->setDatemodif(new \Datetime());
          $produit->setQtestock($qtestock);
    
          $em->persist($sortie);
          $em->flush();
        }
        
        // Rediriger vers la même page

        return $this->redirectToRoute('home_sortie');
    }


}
