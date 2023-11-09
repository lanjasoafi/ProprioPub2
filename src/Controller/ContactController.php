<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/{_locale}/contact', name: 'app_contact', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(MailerInterface $mailer): Response
    {
        // $email = (new Contact())
        //     ->from('hello@example.com')
        //     ->to('you@example.com')
        //     //->cc('cc@example.com')
        //     //->bcc('bcc@example.com')
        //     //->replyTo('fabien@example.com')
        //     //->priority(Email::PRIORITY_HIGH)
        //     ->subject('Time for Symfony Mailer!')
        //     ->text('Sending emails is fun again!')
        //     ->html('<p>See Twig integration for better HTML integration!</p>');

        // $mailer->send($email);
        return $this->render('contact/index.html.twig', [
            'current_menu' => 'Contact',
        ]);
    }
}
