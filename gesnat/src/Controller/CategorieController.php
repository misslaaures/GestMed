<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="home_categorie")
     */

    public function index(CategorieRepository $em)
    {
        $categories = $em->findAll();

        return $this->render('project/categorie.html.twig', array(
          'categories'  => $categories
        ));
    }

    /**
     * @Route("/afficherCategorie", name="afficher_categorie")
     */
    

    public function afficher_categorie(Request $request)
    {
        //dump($request);
        // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);

        // Si Utilisateur trouvé
        if(isset($categorie))
        {
                $id=$categorie->getId();
                $titre=$categorie->getTitre();
                $description=$categorie->getDescription();
        }

        // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
            return new JsonResponse(array('id'=>$id,'titre'=>$titre, 'description'=>$description));
        }
        
    }

    /**
     * @Route("/supprimercategorie", name="supprimer_categorie")
     */

    public function supprimer_categorie(Request $request)
    {
        // dump($request);
        // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);

        $res=false;
        

        // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
            // Supprimer l'entité
            $em->remove($categorie);

            // On déclenche la modification
            $em->flush();
            $res=true;
            return new JsonResponse(array('res'=>$res));
            
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository(Categorie::class)->findAll();  
            
      
              return $this->render('project/categorie.html.twig', array(
                'categories'  => $categories
              ));
            
        }
        
    }

    /**
     * @Route("/enregistercategorie", name="enregistrer_categorie")
     */

    public function enregistrerAction(Request $request): Response
    {
        // Recuperer variable post
        $categid=$request->request->get('categorieid');
        $titre=$request->request->get('titre');
        $description=$request->request->get('description');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        
        if($categid==null)
        {
            // Création de l'entitité
            $categorie = new Categorie();
            $categorie->setTitre($titre);
            $categorie->setDescription($description);
            $categorie->setDatemodif(new \Datetime());

            $em->persist($categorie);
            $em->flush();
        }
        else
        {
            // On récupère l'annonce $id
            $categorie = $em->getRepository(Categorie::class)->find($categid);

            $categorie->setTitre($titre);
            $categorie->setDescription($description);
            $categorie->setDatemodif(new \Datetime());

            $em->flush();

            // Ajouter un message flash dans la session
            
        }

        // Rediriger vers la même page
        return $this->redirectToRoute('home_categorie');

    }


}
