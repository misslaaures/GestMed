<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Acte;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ActeRepository;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class ListeactesController extends AbstractController
{
    /**
     * @Route("/listeactes", name="home_listeactes")
     */

    public function index(ActeRepository $em)
    {
        $actes = $em->findAll();
        $datedujour = new \Datetime();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('project/listeactes.html.twig', [
            'actes' => $actes, 'datedujour' => $datedujour
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listeactes.pdf", [
            "Attachment" => false
        ]);
    }
}