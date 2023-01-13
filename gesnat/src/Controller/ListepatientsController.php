<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientRepository;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class ListepatientsController extends AbstractController
{
    /**
     * @Route("/listepatients", name="home_listepatients")
     */

    public function index(PatientRepository $em)
    {
        $patients = $em->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('project/listepatients.html.twig', [
            'patients' => $patients
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listepatients.pdf", [
            "Attachment" => false
        ]);
    }
}