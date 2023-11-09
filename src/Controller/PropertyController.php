<?php

namespace App\Controller;

use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class PropertyController extends AbstractController
    {

        #[Route('/{_locale}/property', name: 'app_property', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
        public function index(Request $request, PropertyRepository $propertyRepository, PaginatorInterface $paginator): Response
        {
            $keyword = $request->query->get('keyword');
            $type = $request->query->get('type');
            $adress = $request->query->get('adress');
            $surface = $request->query->get('surface');
            $chambre = $request->query->get('chambre');
            $status = $request->query->get('status');
            $price = $request->query->get('price');
        
            $properties = $propertyRepository->findBySearchCriteria($keyword, $type, $adress, $surface, $chambre, $status, $price);
        
            $pagination = $paginator->paginate(
                $properties,
                $request->query->getInt('page', 1),
                6
            );
        
            return $this->render('property/index.html.twig', [
                'current_menu' => 'property',
                'pagination' => $pagination,
            ]);
        }
        

        

}
