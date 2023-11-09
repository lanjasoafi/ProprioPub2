<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PayementController extends AbstractController
{
    #[Route('/stripe-checkout/{token}/{amount}/{description}', name: 'stripe_checkout')]
    public function stripeCheckout($token, $amount, $description): Response
    {
        return $this->render('publier/payement/stripe_checkout.html.twig', [
            'token' => $token,
            'amount' => $amount,
            'description' => $description,
        ]);
    }
}
