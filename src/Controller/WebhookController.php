<?php

// src/Controller/WebhookController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    #[Route('/stripe/webhook', name:'stripe_webhook', methods:['POST'])]
    public function handleWebhook(Request $request): Response
    {
        // Retrieve the JSON payload from the request body
        $payload = json_decode($request->getContent(), true);

        // Handle specific Stripe webhook events
        switch ($payload['type']) {
            case 'payment_intent.succeeded':
                // Payment succeeded, handle accordingly
                // Update your database and fulfill the order
                break;
            case 'payment_intent.failed':
                // Payment failed, handle accordingly
                // Handle the failure, e.g., display an error message to the customer
                break;
            // Add more event handlers for other webhook events as needed
            default:
                return new Response('Invalid webhook event type', 400);
        }

        return new Response('Webhook received', 200);
    }
}
