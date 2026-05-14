<?php
// Service email natif (mail PHP)
class EmailService {
    // Envoi email générique
    public static function send($to, $subject, $body) {
        $headers = "From: support@monasso.eu\r\nContent-Type: text/html; charset=UTF-8";
        return mail($to, $subject, $body, $headers);
    }

    // Email de suspension d'abonnement
    public static function sendSuspension($to, $reason) {
        $subject = 'Votre abonnement est suspendu';
        $body = self::buildSuspensionEmail($reason);
        return self::send($to, $subject, $body);
    }

    // Email de réactivation d'abonnement
    public static function sendReactivation($to) {
        $subject = 'Votre abonnement est réactivé';
        $body = self::buildReactivationEmail();
        return self::send($to, $subject, $body);
    }

    // Email de bienvenue après provisioning
    public static function sendWelcome($to, $association) {
        $subject = 'Bienvenue sur MonAsso';
        $body = self::buildWelcomeEmail($association);
        return self::send($to, $subject, $body);
    }

    private static function buildSuspensionEmail($reason) {
        return "<html><body style='font-family:Titillium Web,sans-serif;'>
            <h2 style='color:#dc2626;'>Abonnement suspendu</h2>
            <p>Votre abonnement MonAsso a été suspendu.</p>
            <p><strong>Raison :</strong> " . htmlspecialchars($reason) . "</p>
            <p>Veuillez régulariser votre situation depuis votre espace client.</p>
            <p>Cordialement,<br>L'équipe MonAsso</p>
        </body></html>";
    }

    private static function buildReactivationEmail() {
        return "<html><body style='font-family:Titillium Web,sans-serif;'>
            <h2 style='color:#16a34a;'>Abonnement réactivé</h2>
            <p>Votre abonnement MonAsso est maintenant réactivé.</p>
            <p>Vous pouvez accéder à l'ensemble de vos services depuis votre espace client.</p>
            <p>Cordialement,<br>L'équipe MonAsso</p>
        </body></html>";
    }

    private static function buildWelcomeEmail($association) {
        return "<html><body style='font-family:Titillium Web,sans-serif;'>
            <h2 style='color:#4f46e5;'>Bienvenue " . htmlspecialchars($association) . " !</h2>
            <p>Votre espace MonAsso est prêt.</p>
            <p>Connectez-vous à votre tableau de bord pour gérer votre abonnement et vos services.</p>
            <p>Cordialement,<br>L'équipe MonAsso</p>
        </body></html>";
    }
}
