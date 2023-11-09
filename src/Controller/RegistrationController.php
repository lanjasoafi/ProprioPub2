<?php

// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailerService;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager, 
        MailerService $mailerService,
        TokenGeneratorInterface $tokenGeneratorInterface): Response
    {
        $user = new User();

        // Assurez-vous de définir la date et l'heure actuelles pour 'created_at'
        $user->setCreatedAt(new \DateTime());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // token

            $token = $tokenGeneratorInterface->generateToken();

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setToken($token);

            $entityManager->persist($user);
            $entityManager->flush();

            // envoie mail

            $mailerService->send(
                $user->getEmail(),
                'Comfirmation du compte Utilisateur',
                'mailValidation.html.twig',
                [
                    'user'=> $user,
                    'token'=>$token,
                    'tokenTime'=>$user->getTokenTime()->format('Y-m-d H:i:s')
                ]
            );

            $this->addFlash(
               'success',
               'Votre compte a bien etes creer. pour l\'activer, verifier votre e-mail'
            );
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    #[Route('/verifed/{token}/{id<\d+>}', name: 'app_confirm_account', methods: ['GET'])]
    public function verify(string $token, User $user, EntityManagerInterface $em){
        if($user->getToken() !== $token){
            throw new AccessDeniedException();
        }
        if($user->getToken() === null ){
            throw new AccessDeniedException();
        }
        if(new \DateTime() > $user->getTokenTime()){
            throw new AccessDeniedException();
        }
        $user->setIsVerified(true);
        $user->setToken(null);
        $em->flush();
    
        $this->addFlash(
            'success',
            'Votre compte a bien été activé. Vous pouvez vous connecter en toute sécurité'
        );
    
        return $this->redirectToRoute('app_login'); // Retournez une redirection ou une réponse appropriée.
    }
    
}
