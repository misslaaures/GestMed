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

class ListeproduitsController extends AbstractController
{
    /**
     * @Route("/listeproduits", name="home_listeproduits")
     */

    public function index(ProduitRepository $em)
    {
        $produits = $em->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('project/listeproduits.html.twig', [
            'produits' => $produits
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listeproduits.pdf", [
            "Attachment" => false
        ]);
    }
}