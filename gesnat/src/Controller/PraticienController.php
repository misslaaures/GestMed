<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Praticien;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PraticienRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class PraticienController extends AbstractController
{
    /**
     * @Route("/praticien", name="home_praticien")
     */

    public function index(PraticienRepository $em)
    {
        $praticiens = $em->findAll();

        return $this->render('project/praticien.html.twig', array(
          'praticiens'  => $praticiens
        ));
    }

     /**
     * @Route("/afficherPraticien", name="afficher_praticien")
     */
    

    public function afficher_praticien(Request $request)
    {
        dump($request);
        // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $praticien = $em->getRepository(Praticien::class)->find($id);

        // Si Utilisateur trouvé
        if(isset($praticien))
        {
                $id=$praticien->getId();
                $nom=$praticien->getNom();
                $datenais=$praticien->getDatenais()->format('Y-m-d');
                $specialite=$praticien->getSpecialite();
                $adresse=$praticien->getAdresse();
                $telephone=$praticien->getTelephone();
                $sexe=$praticien->getSexe();
        }

        // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
            return new JsonResponse(array('id'=>$id,'nom'=>$nom, 'datenais'=>$datenais, 'specialite'=>$specialite, 'adresse'=>$adresse, 'telephone'=>$telephone, 'sexe'=>$sexe));
        }
        
    }

    /**
     * @Route("/supprimerPraticien", name="supprimer_praticien")
     */

    public function supprimer_praticien(Request $request)
    {
      dump($request);  
      // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $praticien = $em->getRepository(praticien::class)->find($id);

        $res=false;

       // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
            // Supprimer l'entité
            $em->remove($praticien);

            // On déclenche la modification
            $em->flush();

            $res=true;

            return new JsonResponse(array('res'=>$res));

            $em = $this->getDoctrine()->getManager();
            $praticiens = $em->getRepository(Praticien::class)->findAll();  
            
      
              return $this->render('project/praticien.html.twig', array(
                'praticiens'  => $praticiens
              ));
        }
    }

    /**
     * @Route("/enregisterpraticien", name="enregistrer_praticien")
     */

    public function enregistrerAction(Request $request): Response
    {
        // Recuperer variable post
        $praticienid=$request->request->get('praticienid');
        $nom=$request->request->get('nom');
        $datenais = new \DateTime($request->request->get('datenais'));
        $sexe=$request->request->get('sexe');
        $specialite=$request->request->get('specialite');
        $adresse=$request->request->get('adresse');
        $telephone=$request->request->get('telephone');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        
        if($praticienid==null)
        {
            // Création de l'entitité
            $praticien = new Praticien();
            $praticien->setNom($nom);
            $praticien->setDatenais($datenais);
            $praticien->setSexe($sexe);
            $praticien->setSpecialite($specialite);
            $praticien->setAdresse($adresse);
            $praticien->setTelephone($telephone);
            $praticien->setDatemodif(new \Datetime());

            $em->persist($praticien);
            $em->flush();
            
        }
        else
        {
            // On récupère l'annonce $id
            $praticien = $em->getRepository(Praticien::class)->find($praticienid);

            $praticien->setNom($nom);
            $praticien->setDatenais($datenais);
            $praticien->setSexe($sexe);
            $praticien->setSpecialite($specialite);
            $praticien->setAdresse($adresse);
            $praticien->setTelephone($telephone);
            $praticien->setDatemodif(new \Datetime());

            $em->flush();

            // Ajouter un message flash dans la session
            
        }

        // Rediriger vers la même page
        return $this->redirectToRoute('home_praticien');

    }
    
}
