<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertySingleController extends AbstractController
{
    #[Route('/{_locale}/property-single/{id}', name: 'app_property_single', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(int $id ,
    PropertyRepository $PropertyRepository, 
    ImageRepository $imageRepository,
    UserRepository $userRepository): Response
    {
        
    // recupere l'annonce par son id
        $property = $PropertyRepository->find($id);
        
    // recupere l'id de l'utilisateur via l'annonce $property
        $user = $property->getUser();
    // recuperer l'image
        $image = $imageRepository->findBy(['property' => $id]);
    // recuperer l'utilisateur via son id
        $utilasteur = $userRepository->find($user);

        return $this->render('property_single/index.html.twig', [
            'property' => $property,
            'images' => $image,
            'user'=>$utilasteur
        ]);
        
    }
}
