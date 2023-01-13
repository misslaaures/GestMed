<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitRepository;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class ListeproduitsalerteController extends AbstractController
{
    /**
     * @Route("/listeproduitsalerte", name="home_listeproduitsalerte")
     */

    public function index(ProduitRepository $em)
    {
        $produitsalerte = $em->findAll();

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('project/listeproduitsalerte.html.twig', [
            'produitsalerte' => $produitsalerte
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listeproduitsalerte.pdf", [
            "Attachment" => false
        ]);
    }
}