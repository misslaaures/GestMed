<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Praticien;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PraticienRepository;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class ListepraticiensController extends AbstractController
{
    /**
     * @Route("/listepraticiens", name="home_listepraticiens")
     */

    public function index(PraticienRepository $em)
    {
        $praticiens = $em->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('project/listepraticiens.html.twig', [
            'praticiens' => $praticiens
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listepraticiens.pdf", [
            "Attachment" => false
        ]);
    }
}