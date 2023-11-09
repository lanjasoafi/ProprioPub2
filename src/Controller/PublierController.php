<?php
namespace App\Controller;


use Stripe\Stripe;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\Property;
use App\Form\PropertyType;
use Stripe\Checkout\Session;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublierController extends AbstractController
{
    private $propertyRepository;
    private $security;
    private $entityManager;
    private $freeAdsLimit = 10; // Limite de 10 annonces gratuites

    public function __construct(
        PropertyRepository $propertyRepository,
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/{_locale}/publier', name: 'app_publier', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function publier(Request $request): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $user = $this->security->getUser();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            Stripe::setApiKey('sk_test_51O30VxAzl3tkC7gZ1G9ZC3U5A9RuGR1GGl3IpTzzwlfJvnnAD1stqToqAE9n0PG6uNbMc9gWqIDxvF3e5UJcOkz' .
                'E00vY8qcoEK');
            
            $uploadedFiles = $request->files->get('property')['images'] ?? [];
            
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $uploadedFile) {
                    if ($uploadedFile instanceof UploadedFile) {
                        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                        $uploadedFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                        
                        $image = new Image();
                        $image->setImage($newFilename);
                        $image->setProperty($property);
                        $this->entityManager->persist($image);
                    }
                }
            }
            
            // Set the user for the property based on the currently authenticated user
            $user = $this->security->getUser();

            // Associez l'utilisateur à la propriété
            $property->setUser($user);
            
            $request->getSession()->set('property_data', $property);

            
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => 'price_1O30fgAzl3tkC7gZ9TnPq6Dg',
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('confirmation', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('app_publier', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
            
            // After making all necessary changes, persist the property to the database
            $this->entityManager->persist($property);
            $this->entityManager->flush();
            
            return $this->redirect($session->url);
        }
        
        return $this->render('publier/index.html.twig', [
            'form' => $form->createView(),
          
        ]);
    }
    
    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(Request $request): Response
    {
        $property = $request->getSession()->get('property_data');
        
        if ($property instanceof Property) {
            // No need to persist and flush the property here
        }
        
        return $this->render('publier/payement/confirmation.html.twig');
    }
}
