<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Facturation;
use App\Entity\Produit;
use App\Entity\Compteur;
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
 * Facture controller.
 *
 */

class FactureController extends AbstractController
{
    /** 
     * 
     * @Route("/homefacturation", name="home_facturation")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Compteur::class);
        $compteur = $repository->find(1);
        $numcom = $compteur->getNumCom();
        $numcom = $numcom + 1;
        $facture = new Facture();
        $facturation = new Facturation();
        $formFacture = $this->createForm(FactureType::class, $facture);
        $formFacture->handleRequest($request);
        $formFacturation = $this->createForm(FacturationType::class, $facturation);
        $formFacturation->handleRequest($request);
        $mt = 0;
        $mtr = 0;
        $mtt = 0;
        $lig = 0;
        $Tabcomm = [];
        
        
        session_start();
        session_destroy();
        return $this->render('project/facture.html.twig', [
            'formFacture' => $formFacture->createView(),
            'formFacturation' => $formFacturation->createView(), 'lcomm'=>$Tabcomm,
            'numc'=>$numcom, 'mt'=>$mt, 'mtr'=>$mtr, 'mtt'=>$mtt, 'lig'=>$lig
        ]);
    }

    /** 
     * 
     * @Route("/facturation", name="facturation", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $res = true;
        $repository = $this->getDoctrine()->getRepository(Compteur::class);
        $compteur = $repository->find(1);
        $numcom = $compteur->getNumCom();
        $numcom = $numcom + 1;
        $facture = new Facture();
        $facturation = new Facturation();
        $formFacture = $this->createForm(FactureType::class, $facture);
        $formFacture->handleRequest($request);
        $formFacturation = $this->createForm(FacturationType::class, $facturation);
        $formFacturation->handleRequest($request);
        $mt = 0;
        $mtr = 0;
        $mtt = 0;
        $lig = 0;
        $rem = 0;
        
        if ($res == true){
            $session = $request->getSession();
        }
        
        if(!$session->has('facture'))
        {
            $session->set('facture', array());
        }
        $Tabcomm = $session->get('facture', []);
      
        if ($formFacture->isSubmitted() || $formFacturation->isSubmitted()){
            $choix = $request->get('bt');
            if($choix == "Editer"){
                $em=$this->getDoctrine()->getManager();
                

                $lig = sizeof($Tabcomm);
                for($i = 1;$i<=$lig;$i++){
                    
                    $facturation -> setProduit($Tabcomm[$i]->getProduit());
                    $facturation -> setIdfacture($numcom);
                    //$em = $this->getDoctrine()->getManager();
                    $prix = $em->getRepository(Produit::class)->find($Tabcomm[$i]->getProduit())->getPrix();
                    $facturation -> setPrix($prix);
                    $facturation -> setQteP($Tabcomm[$i]->getQteP());
                    $facturation -> setMt($prix * $Tabcomm[$i]->getQteP());
                    $facturation -> setRem($Tabcomm[$i]->getRem());
                    $mtr = ($prix * $Tabcomm[$i]->getQteP()) - ((($prix * $Tabcomm[$i]->getQteP()) * $Tabcomm[$i]->getRem()) * 0.01);
                    $facturation -> setMtr($mtr);
                    //$em->persist($facturation);
                    $mtt = $mtt + $mtr;
                    $produit = $em->getRepository(Produit::class)->find($Tabcomm[$i]->getProduit());
                    $qtestock = $produit->getQtestock();
                    $qtestock=$qtestock-($Tabcomm[$i]->getQteP());
                    $produit -> setQtestock($qtestock);
                    $em->persist($produit);
                    $em->flush();

                }
                //$facture->setId($numcom);
                $facture->setDateFact(new \Datetime());
                $facture -> setMtt($mtt);
                $patient = $facture -> getPatient();
                $compteur -> setNumcom($numcom);
                $em->persist($facture);
                $em->persist($compteur);
                $em->flush();
                
                $datedujour = new \Datetime();
                // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('project/factureedit.html.twig', [
            'formFacture' => $formFacture->createView(),
            'formFacturation' => $formFacturation->createView(), 'lcomm'=>$Tabcomm,
            'numc'=>$numcom, 'mt'=>$mt, 'mtr'=>$mtr, 'mtt'=>$mtt, 'lig'=>$lig, 'patient'=>$patient, 'datedujour' => $datedujour
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("facturepatient.pdf", [
            "Attachment" => true
        ]);
        $session->set('facture', []);
        return $this->redirectToRoute('home_facturation');    
            }
            elseif($choix == "Ajouter"){
                $mt = $facturation->getPrix()*$facturation->getQteP();
                $mtr = $mt - ($mt * $facturation->getRem() * 0.01);
                $facturation->setMt($mt);
                $facturation->setMtr($mtr);
                $facturation->setIdfacture($numcom);
                //dump($facturation);
                $lig = sizeof($Tabcomm)+1;
                //$facturation->setId($lig);
                $Tabcomm[$lig] = $facturation;
                $session->set('facture', $Tabcomm);

            }
            
            
        }
        $res=false;
        return $this->render('project/facture.html.twig', [
            'formFacture' => $formFacture->createView(),
            'formFacturation' => $formFacturation->createView(), 'lcomm'=>$Tabcomm,
            'numc'=>$numcom, 'mt'=>$mt, 'mtr'=>$mtr, 'mtt'=>$mtt, 'lig'=>$lig
        ]);

    }

     /** 
     * 
     * @Route("/supprimer_facturation", name="remove")
     */
    public function remove(Request $request)
    {
        $session = $request->getSession();
        $Tabcomm = $session->get('facture', []);
        $id=$request->request->get('id');
        unset($Tabcomm[$id]);
        $session->set('facture', $Tabcomm);
        return $this->redirectToRoute("facturation");
    }
}

