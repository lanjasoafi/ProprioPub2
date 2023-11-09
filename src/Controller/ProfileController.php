<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/{_locale}/profile', name: 'app_profile', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(): Response
    {
        $user = $this->security->getUser();
        
        return $this->render('publier/profile/index.html.twig', [
            'user' => $user,
            'current_menu' => 'profile',
            'titre_page' => 'Mon profile'
        ]);
    }

    #[Route('/{_locale}/profile/edite', name: 'app_edite', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Get the currently logged-in user
       
     
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('photo')->getData();
          
            if ($uploadedFile instanceof UploadedFile) {
                // Generate a unique filename
                $newFileName = uniqid().'.'.$uploadedFile->guessExtension();

                // Define the target directory for file uploads
                $uploadDirectory = $this->getParameter('photos_directory');

                // Move the uploaded file to the target directory
                try {
                    $uploadedFile->move(
                        $uploadDirectory,
                        $newFileName
                    );

                    // Update the user's photo property with the new filename
                    $user->setPhoto($newFileName);

                    // Persist and flush the user entity
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // Redirect the user to another page or show a success message
                    // For example, you can redirect to the user's profile page
                    return $this->redirectToRoute('app_profile');
                } catch (FileException $e) {
                    // Handle upload errors here, e.g., log the error or display a user-friendly message
                    $this->addFlash('error', 'An error occurred while uploading the file.');
                }
            }
        }

        return $this->render('publier/profile/edite.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'titre_page'=>'Modifier mon profile'
        ]);
    }
}
