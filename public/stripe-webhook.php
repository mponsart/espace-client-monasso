<?php
// Stripe Webhook endpoint (paiement, suspension, provisioning)

require_once __DIR__ . '/../Service/StripeService.php';
require_once __DIR__ . '/../Service/CpanelService.php';
require_once __DIR__ . '/../Service/EmailService.php';
require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../Model/Subscription.php';
require_once __DIR__ . '/../Model/Notification.php';

// Lecture du payload Stripe
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

$stripeService = new StripeService();
$event = $stripeService->validateWebhook($payload, $sig_header);

if (!$event) {
    http_response_code(400);
    exit('Invalid signature');
}

switch ($event['type']) {
    case 'invoice.payment_succeeded':
        $stripeService->handlePaymentSucceeded($event['data']['object']);
        break;
    case 'invoice.payment_failed':
        $stripeService->handlePaymentFailed($event['data']['object']);
        break;
    case 'customer.subscription.deleted':
        $stripeService->handleSubscriptionDeleted($event['data']['object']);
        break;
    // Ajoute d'autres événements si besoin
    default:
        // Ignore
        break;
}

http_response_code(200);
