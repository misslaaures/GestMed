<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Facturation;
use App\Entity\Produit;
use App\Form\FactureType;
use App\Form\FacturationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FacturationRepository;
use App\Repository\FactureRepository;
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

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Facturation controller.
 *
 */

class FacturationController extends AbstractController
{
    /** 
     * 
     * @Route("/chiffre_d_affaire_home", name="chiffre_home")
     */
    public function index(FactureRepository $em, Request $request)
    {
        $factures = $em->findAll();
        $message = "";
        return $this->render('project/chiffre_d_affaire.html.twig', [
            'factures' => $factures, 'message' => $message
        ]);
    }
    
    /**
     * @Route("/chiffre_d_affaire", name="voir_chiffre_d_affaire", methods={"GET", "POST"})
     */
    public function voir_chiffre(FactureRepository $em, Request $request)
    {
        
        $factures = $em->findAll();
        $datedebut = new \DateTime($request->request->get('datedebut'));
        $datefin = new \DateTime($request->request->get('datefin'));
        $ca = 0;
        if($datedebut > $datefin){
            $message = "Veuillez prendre des dates valides";
            return $this->render('project/chiffre_d_affaire.html.twig', [
                'message' => $message
            ]);
        }
        else{
            // Configure Dompdf according to your needs
         //$pdfOptions = new Options();
         //$pdfOptions->set('defaultFont', 'Arial');
         
         //require_once('vendor/dompdf/dompdf_config.inc.php');

         // Instantiate Dompdf with our options
         $dompdf = new DOMPDF();
         
         // Retrieve the HTML generated in our twig file
         $html = $this->renderView('project/etatchiffredaffaire.html.twig', [
             'factures' => $factures, 'datedebut' => $datedebut, 'datefin' => $datefin, 'ca' => $ca
         ]);
         
         // Load HTML to Dompdf
         $dompdf->loadHtml($html);
         
         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A4', 'portrait');
 
         // Render the HTML as PDF
         $dompdf->render();
 
         // Output the generated PDF to Browser (inline view)
         $output = $dompdf->output();
         $dompdf->stream("chiffre.pdf", [
             "Attachment" => true
         ]);

         //return $this->redirectToRoute('home_produit');
        }
         
        
     }  

}


