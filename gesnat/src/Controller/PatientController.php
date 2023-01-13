<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE


class PatientController extends AbstractController
{
    /**
     * @Route("/patient", name="home_patient")
     */

    public function index(PatientRepository $em)
    {
        $patients = $em->findAll();

        return $this->render('project/patient.html.twig', array(
          'patients'  => $patients
        ));
    }

     /**
     * @Route("/afficherPatient", name="afficher_patient")
     */
    

    public function afficher_patient(Request $request)
    {
        dump($request);
        // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($id);

        // Si Utilisateur trouvé
        if(isset($patient))
        {
                $id=$patient->getId();
                $nompat=$patient->getNompat();
                $datenaispat=$patient->getDatenaispat()->format('Y-m-d');
                $fonctionpat=$patient->getfonctionpat();
                $adressepat=$patient->getAdressepat();
                $telephonepat=$patient->getTelephonepat();
                $sexepat=$patient->getSexe();
        }

        // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
          return new JsonResponse(array('id'=>$id,'nompat'=>$nompat, 'datenaispat'=>$datenaispat, 'adressepat'=>$adressepat, 'telephonepat'=>$telephonepat, 'sexepat'=>$sexepat, 'fonctionpat'=>$fonctionpat));
        }
        
    }

    /**
     * @Route("/supprimerPatient", name="supprimer_patient")
     */

    public function supprimer_patient(Request $request)
    { 
      // Recuperer variable post
        $id=$request->request->get('id');

        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($id);

        $res=false;

       // s'il s'agit d'une requete de type ajax
        if ($request->isXmlHttpRequest())
        {
            // Supprimer l'entité
            $em->remove($patient);

            // On déclenche la modification
            $em->flush();

            $res=true;
            return new JsonResponse(array('res'=>$res));
            $em = $this->getDoctrine()->getManager();
            $patients = $em->getRepository(Patient::class)->findAll();  
            
      
              return $this->render('project/patient.html.twig', array(
                'patients'  => $patients
              ));
            
        }
        
        
    }

    /**
     * @Route("/enregisterpatient", name="enregistrer_patient")
     */

    public function enregistrerAction(Request $request): Response
    {
        // Recuperer variable post
        $patientid=$request->request->get('patientid');
        $nompat=$request->request->get('nompat');
        $datenaispat= new \Datetime($request->request->get('datenaispat'));
        $sexepat=$request->request->get('sexe');
        $fonctionpat=$request->request->get('fonctionpat');
        $adressepat=$request->request->get('adressepat');
        $telephonepat=$request->request->get('telephonepat');
        // Afficher le résultat de la recherche des identifiants
        $em = $this->getDoctrine()->getManager();
        
        if($patientid==null)
        {
            // Création de l'entitité
            $patient = new Patient();
            $patient->setNompat($nompat);
            $patient->setDatenaispat($datenaispat);
            $patient->setSexe($sexepat);
            $patient->setFonctionpat($fonctionpat);
            $patient->setAdressepat($adressepat);
            $patient->setTelephonepat($telephonepat);
            $patient->setDatemodif(new \Datetime());

            $em->persist($patient);
            $em->flush();
        }
        else
        {
            // On récupère l'annonce $id
            $patient = $em->getRepository(Patient::class)->find($patientid);

            $patient->setNompat($nompat);
            $patient->setDatenaispat($datenaispat);
            $patient->setSexe($sexepat);
            $patient->setFonctionpat($fonctionpat);
            $patient->setAdressepat($adressepat);
            $patient->setTelephonepat($telephonepat);
            $patient->setDatemodif(new \Datetime());

            $em->flush();

            // Ajouter un message flash dans la session
            
        }

        // Rediriger vers la même page
        return $this->redirectToRoute('home_patient');

    }
    
}
