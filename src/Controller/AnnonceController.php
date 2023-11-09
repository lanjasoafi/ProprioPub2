<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Property;
use App\Form\AnnoceType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AnnonceController extends AbstractController
{
    private $propertyRepository;
    private $security;
    private $entityManager;

    public function __construct(
        PropertyRepository $propertyRepository,
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/{_locale}/annonce', name: 'app_annonce', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(): Response
    {
        $user = $this->security->getUser();
        $annonces = $this->propertyRepository->findBy(['User' => $user]);

        return $this->render('publier/annonce/annonce.html.twig', [
            'annonces' => $annonces,
            'current_menu' => 'annonce',
            'titre_page' => 'Annonces',
        ]);
    }

    #[Route('/supprimer-annonce/{id}', name: 'supprimer_annonce')]
    public function supprimerAnnonce(Property $annonce): Response
    {
        $user = $this->security->getUser();

        if ($annonce->getUser() !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à supprimer cette annonce.');
        }

        $this->entityManager->remove($annonce);
        $this->entityManager->flush();

        // Ajoutez un message flash pour indiquer que l'annonce a été supprimée avec succès
        $this->addFlash('success', 'L\'annonce a été supprimée avec succès.');

        return $this->redirectToRoute('app_annonce');
    }

    #[Route('/{_locale}/modifier-annonce/{id}', name: 'modifier_annonce', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function modifierAnnonce(Request $request, Property $annonce): Response
    {
        $user = $this->security->getUser();

        if ($annonce->getUser() !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier cette annonce.');
        }

        $form = $this->createForm(AnnoceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
                        $image->setProperty($annonce);
                        $this->entityManager->persist($image);
                    }
                }
            }
            $this->entityManager->persist($annonce);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_annonce');
        }

        return $this->render('publier/annonce/modifier_annonce.html.twig', [
            'form' => $form->createView(),
            'current_menu' => 'annonce',
            'titre_page' => 'Modifier Annonce',
        ]);
    }
}
