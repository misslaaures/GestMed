<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
//use App\Repository\PatientRepository;


/***************************/ 
/* Radek HULAN             */
/* http://hulan.info/blog/ */
/***************************/  

class html2pdfController extends AbstractController
{
    /**
     * @Route("/essaie", name="pdf_html")
     */

    public function index()
    {
        
require('html2pdf.php');
       
        if(isset($_POST['html']))
        {
            $pdf = new createPDF(
                $_POST['html'],   // html text to publish
                $_POST['title'],  // article title
                $_POST['url'],    // article URL
                $_POST['author'], // author name
                time()
            );
            $pdf->run();
        }
       
    }
}
