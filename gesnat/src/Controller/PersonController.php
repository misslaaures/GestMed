<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\City;
use App\Entity\Neighborhood;
use App\Form\PersonType;
use App\Form\CityType;
use App\Form\NeighborhoodType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PersonRepository;
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

/**
 * Person controller.
 *
 */

class PersonController extends AbstractController
{
    /** 
     * 
     * @Route("/person", name="person")
     */
    public function index()
    {
        $person = new Person();

        $form = $this->createForm(PersonType::class, $person);
        return $this->render('project/person.html.twig', [
            'formPerson' => $form->createView()
        ]);
    }

   
    /**
     * Returns a JSON string with the neighborhoods of the City with the providen id.
     * 
     * @Route("/personlist", name="person_list_neighborhoods")
     * @param Request $request
     * @return JsonResponse
     */
    public function listNeighborhoodsOfCityAction(Request $request)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $neighborhoodsRepository = $em->getRepository(Neighborhood::class);
        
        // Search the neighborhoods that belongs to the city with the given id as GET parameter "cityid"
        $neighborhoods = $neighborhoodsRepository->createQueryBuilder("q")
            ->where("q.city = :cityid")
            ->setParameter("cityid", $request->query->get("cityid"))
            ->getQuery()
            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($neighborhoods as $neighborhood){
            $responseArray[] = array(
                "id" => $neighborhood->getId(),
                "name" => $neighborhood->getName()
            );
        }
        
        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }
    
}


