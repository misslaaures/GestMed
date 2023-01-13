<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Acte;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ActeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class ActeController extends AbstractController
{
    /**
     * @Route("/acte", name="home_acte")
     */

    public function index(ActeRepository $em)
    {
        $actes = $em->findAll();

        return $this->render('project/acte.html.twig', array(
          'actes'  => $actes
        ));
    }

    
    /**
     * @Route("/afficherActe", name="afficher_acte")
     */
    

    public function afficher_acte(Request $request)
    {
        dump($request);
        // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $acte = $em->getRepository(Acte::class)->find($id);

        // Si Utilisateur trouvé
        if(isset($acte))
        {
                $id=$acte->getId();
                $libact=$acte->getLibact();
                $prixact=$acte->getPrixact();
        }

        // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
            return new JsonResponse(array('id'=>$id,'libact'=>$libact, 'prixact'=>$prixact));
        }
        
    }

    /**
     * @Route("/supprimerActe", name="supprimer_acte")
     */

    public function supprimer_acte(Request $request)
    {
      //dump($request);  
      // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $acte = $em->getRepository(Acte::class)->find($id);

        $res=false;
        

       // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
             // Supprimer l'entité
             $em->remove($acte);

             // On déclenche la modification
             $em->flush();
             $res=true;
            
              // Rediriger vers la même page
        return $this->redirectToRoute('home_acte');

        }
        
        
    }

    /**
     * @Route("/enregisteracte", name="enregistrer_acte")
     */

    public function enregistrerAction(Request $request): Response
    {
        // Recuperer variable post
        $acteid=$request->request->get('acteid');
        $libact=$request->request->get('libact');
        $prixact=$request->request->get('prixact');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        
        if($acteid==null)
        {
            // Création de l'entitité
            $acte = new Acte();
            $acte->setLibact($libact);
            $acte->setPrixact($prixact);
            $acte->setDatemodif(new \Datetime());

            $em->persist($acte);
            $em->flush();
        }
        else
        {
            // On récupère l'annonce $id
            $acte = $em->getRepository(Acte::class)->find($acteid);

            $acte->setLibact($libact);
            $acte->setPrixact($prixact);
            $acte->setDatemodif(new \Datetime());

            $em->flush();

            // Ajouter un message flash dans la session
            
        }

        // Rediriger vers la même page
        return $this->redirectToRoute('home_acte');

    }

}
