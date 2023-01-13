<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;         

use App\Entity\Consultation;
use App\Entity\Patient;
use App\Entity\Praticien;
use App\Form\ConsultationType;
use App\Form\PatientType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConsultationRepository;
use App\Repository\PatientRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;  // AJAX
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class ConsultationController extends AbstractController
{
    /**
     * @Route("/consultation", name="home_consultation")
     */

    public function index()
    {
        $consultation = new Consultation();

        $formConsultation = $this->createForm(ConsultationType::class, $consultation);
        return $this->render('project/consultations.html.twig', [
            'formConsultation' => $formConsultation->createView()
        ]);
    }

   
    /**
     * @Route("/consultations", name="new_consultation", methods={"GET", "POST"})
     */
    public function create(Request $request) : Response
    {
        
        $consultation = new Consultation();
        $formConsultation = $this->createForm(ConsultationType::class, $consultation);
        $formConsultation->handleRequest($request);
        $consultation->setDateconsult(new \Datetime());
        $consultation->setDatemodif(new \Datetime());  
        if ($formConsultation->isSubmitted()){
            $choix = $request->get('bt');
            if($choix == "Enregistrer"){
                $em=$this->getDoctrine()->getManager();
                $em->persist($consultation);
                $em->flush();
            }
        }

            return $this->redirectToRoute('home_consultation');
    }
    

    /**
     * @Route("/consultations_patient_home", name="voir_consultation_home", methods={"GET", "POST"})
     */
    public function home_voir(ConsultationRepository $em, PatientRepository $en, Request $request)
    {
        $consult = $em->findAll();
        $patients = $en->findAll();
        
        return $this->render('project/consultationspatient.html.twig', [
            'patients' => $patients, 'consult' => $consult
        ]);
     }  

     /**
     * @Route("/consultations_patient", name="voir_consultation", methods={"GET", "POST"})
     */
    public function voir(ConsultationRepository $em, PatientRepository $en, Request $request)
    {
        
        $consult = $em->findAll();
        $patients = $en->findAll();
        $patientid=$request->request->get('type');
        $patient = $en->find($patientid);
         // Configure Dompdf according to your needs
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
         
         // Instantiate Dompdf with our options
         $dompdf = new Dompdf($pdfOptions);
         
         // Retrieve the HTML generated in our twig file
         $html = $this->renderView('project/listeconsultationspatient.html.twig', [
             'consult' => $consult, 'patient' => $patient
         ]);
         
         // Load HTML to Dompdf
         $dompdf->loadHtml($html);
         
         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A4', 'portrait');
 
         // Render the HTML as PDF
         $dompdf->render();
 
         // Output the generated PDF to Browser (inline view)
         $dompdf->stream("listeconsultationspatient.pdf", [
             "Attachment" => false
         ]);

         //return $this->redirectToRoute('voir_consultation_home');
        
     }  
    
}

