<?php

namespace App\Controller;

use App\Entity\Consulter;
use App\Entity\Patient;
use App\Entity\Consultation;
use App\Form\ConsulterType;
use App\Form\PatientType;
use App\Form\ConsultationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConsulterRepository;
use App\Repository\ConsultationRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE

// Include JSON Response
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Consulter controller.
 *
 */

class ConsulterController extends AbstractController
{
    /** 
     * 
     * @Route("/consulter", name="consulter_dossier_patient")
     */
    public function index(ConsultationRepository $em)
    {
        
        $consulter = new Consulter();
        $consult = $em->findAll();
        $formConsulter = $this->createForm(ConsulterType::class, $consulter);
        //$formConsulter->handleRequest($request);
        $patient = "";

        $tab = [];
        
        
        //session_start();
        //session_destroy();

        return $this->render('project/consulter.html.twig', [
            'formConsulter' => $formConsulter->createView(),'consult' => $consult, 'patient' => $patient, 'tab' => $tab
        ]);
    }

   
    /**
     * Returns a JSON string with the consultations of the Patient with the providen id.
     * 
     * @Route("/consulterlist", name="consulter_list_consultations")
     * @param Request $request
     * @return JsonResponse
     */
    public function listConsultationsOfPatientAction(Request $request)
    {
        
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $consultationsRepository = $em->getRepository(Consultation::class);
        
        // Search the consultations that belongs to the patient with the given id as GET parameter "patientid"
        $consultations = $consultationsRepository->createQueryBuilder("q")
            ->where("q.patient = :patientid")
            ->setParameter("patientid", $request->query->get("patientid"))
            ->getQuery()
            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($consultations as $consultation){
            $responseArray[] = array(
                "id" => $consultation->getId(),
                "motif" => $consultation->getMotif(),
                
            );
        }
        
        // Return array with structure of the consultations of the providen patient id
        return new JsonResponse($responseArray);
       
        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }

    /** 
     * 
     * @Route("/visualiser", name="visualiser_dossier_patient")
     */
    public function visualiser(ConsultationRepository $em,Request $request)
    {
        
        if ($formConsulter->isSubmitted()){
            $choix = $request->get('bt');
            if($choix == "Visualiser"){
                $consult = $em->findAll();
                $patient = $consulter -> getPatient();
                return $this->render('project/consulter.html.twig', [
                    'formConsulter' => $formConsulter->createView(), 'consult'=>$consult, 'patient'=>$patient
                ]);  
            }
        }
        $res = false;
    }
    
}


