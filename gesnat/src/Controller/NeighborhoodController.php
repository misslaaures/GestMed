<?php

namespace App\Controller;

use App\Entity\Neighborhood;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NeighborhoodRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Cookie;  // COOKIE

// Include JSON Response
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 *  Neighborhood controller.
 *
 */

class NeighborhoodController extends AbstractController
{
    
    public function index()
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

  

}


