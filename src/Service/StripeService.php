<?php
// Service Stripe natif (utilise l'API REST Stripe)
class StripeService {
    private $secret;
    private $public;
    public function __construct() {
        $config = require __DIR__ . '/../../config/stripe.php';
        $this->secret = $config['secret_key'];
        $this->public = $config['public_key'];
    }
    public function createCheckoutSession($user, $price) {
        $data = [
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'customer_email' => $user->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Abonnement MonAsso'],
                    'unit_amount' => $price * 100,
                    'recurring' => ['interval' => 'year'],
                ],
                'quantity' => 1,
            ]],
            'success_url' => 'https://monasso.eu/dashboard?success=1',
            'cancel_url' => 'https://monasso.eu/dashboard?canceled=1',
        ];
        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
        curl_setopt($ch, CURLOPT_USERPWD, $this->secret . ':');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    // Validation du webhook Stripe
    public function validateWebhook($payload, $sig_header) {
        $config = require __DIR__ . '/../../config/stripe.php';
        $endpoint_secret = $config['webhook_secret'];
        if (!$endpoint_secret) return false;
        // Vérification simple (à améliorer avec la lib officielle si besoin)
        $event = json_decode($payload, true);
        if (!$event) return false;
        // TODO: vérifier la signature Stripe (ici simplifié)
        return $event;
    }

    // Paiement réussi : active l'abonnement, provisioning, email
    public function handlePaymentSucceeded($invoice) {
        $customerId = $invoice['customer'];
        $subscriptionId = $invoice['subscription'];
        $user = User::findByStripeCustomerId($customerId);
        if (!$user) return;
        Subscription::activate($user->id, $subscriptionId);
        // Provisioning cPanel
        $cpanel = new CpanelService();
        $cpanel->provisionUser($user);
        // Email de confirmation
        EmailService::sendWelcome($user->email, $user->association);
        Notification::create($user->id, 'Abonnement activé après paiement.');
        // Nettoie la metadata de suspension si existante
        $this->updateSuspensionMetadata($subscriptionId, null);
    }

    // Paiement échoué : suspend l'abonnement, email, metadata
    public function handlePaymentFailed($invoice) {
        $customerId = $invoice['customer'];
        $subscriptionId = $invoice['subscription'];
        $user = User::findByStripeCustomerId($customerId);
        if (!$user) return;
        Subscription::suspend($user->id, $subscriptionId);
        // Ajoute la raison dans la metadata Stripe
        $this->updateSuspensionMetadata($subscriptionId, 'Paiement échoué');
        // Email d’alerte
        EmailService::sendSuspension($user->email, 'Paiement échoué');
        Notification::create($user->id, 'Abonnement suspendu (paiement échoué).');
    }

    // Abonnement supprimé : suspension définitive
    public function handleSubscriptionDeleted($subscription) {
        $subscriptionId = is_array($subscription) ? $subscription['id'] : $subscription;
        $user = User::findByStripeSubscriptionId($subscriptionId);
        if (!$user) return;
        Subscription::suspend($user->id, $subscriptionId);
        $this->updateSuspensionMetadata($subscriptionId, 'Abonnement supprimé');
        EmailService::sendSuspension($user->email, 'Abonnement supprimé');
        Notification::create($user->id, 'Abonnement résilié.');
    }

    // Ajoute ou supprime la metadata de suspension Stripe
    public function updateSuspensionMetadata($subscriptionId, $reason) {
        $url = 'https://api.stripe.com/v1/subscriptions/' . $subscriptionId;
        $data = $reason ? ['metadata[suspension_reason]' => $reason] : ['metadata[suspension_reason]' => ''];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secret . ':');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
