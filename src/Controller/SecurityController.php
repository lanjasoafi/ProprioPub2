<?php

// App\Controller\SecurityController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_publier');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
         // Check if there is a CustomUserMessageAccountStatusException
         if ($error instanceof CustomUserMessageAccountStatusException) {
            // Add a custom flash message for UserChecker
            $this->addFlash('danger', $error->getMessage());
        }

        // Check if there is an error message from the authentication (e.g., bad credentials)
        $authError = $this->getAuthenticationError($request);

        if ($authError !== null) {
            if ($authError->getMessageKey() === 'Invalid credentials.') {
                $this->addFlash('danger', 'Email ou mot de passe erroné');
            } else {
                $this->addFlash('danger', $authError->getMessage());
            }
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $authError, // Use the authentication error here
        ]);
    }

    // Function to check and get the authentication error
    private function getAuthenticationError(Request $request)
    {
        $session = $request->getSession();
        $error = $session->get('_security.last_error');

        if (is_a($error, CustomUserMessageAccountStatusException::class)) {
            return null;
        }

        return $error;
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
