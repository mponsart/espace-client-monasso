<?php
// Service cPanel UAPI natif (inspiré de mponsart/cpanelmanager)
class CpanelService {
    private $host;
    private $username;
    private $token;
    private $port;

    public function __construct() {
        $config = require __DIR__ . '/../../config/cpanel.php';
        $this->host = $config['host'] ?? '';
        $this->username = $config['username'] ?? '';
        $this->token = $config['token'] ?? '';
        $this->port = $config['port'] ?? 2083;
    }

    /**
     * Appel API cPanel UAPI (méthode moderne)
     * Inspiré de : mponsart/cpanelmanager - app/Services/CpanelService.php
     */
    private function callUapi($module, $function, $params = []) {
        if (empty($this->host) || empty($this->username) || empty($this->token)) {
            throw new \Exception('Configuration cPanel incomplète. Vérifiez CPANEL_HOST, CPANEL_USERNAME et CPANEL_TOKEN.');
        }

        $url = sprintf(
            'https://%s:%d/execute/%s/%s',
            $this->host,
            $this->port,
            urlencode($module),
            urlencode($function)
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: cpanel {$this->username}:{$this->token}"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Désactivable si certificat valide
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 403) {
            throw new \Exception("Le token API n'a pas la permission d'accéder au module {$module}.");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Réponse cPanel non-JSON [{$module}::{$function}]");
        }

        // Vérification des erreurs UAPI
        if (isset($data['errors']) && !empty($data['errors'])) {
            throw new \Exception(
                sprintf('Erreur cPanel [%s::%s] : %s', $module, $function, implode(', ', (array) $data['errors']))
            );
        }

        return $data;
    }

    /**
     * Crée le dossier utilisateur via UAPI Fileman::mkdir
     * Inspiré de : mponsart/cpanelmanager - app/Http/Controllers/AssociationController.php
     */
    public function createUserFolder($subdomain) {
        // Chemin configurable via .env (Paheko: /home/monasso/users)
        $config = require __DIR__ . '/../../config/cpanel.php';
        $usersPath = $config['users_path'] ?? '/home/monasso/users';
        
        // Utilise UAPI Fileman pour créer le dossier
        // Documentation: https://api.docs.cpanel.net/whm/uapi/endpoint/fileman--mkdir
        return $this->callUapi('Fileman', 'mkdir', [
            'dir' => $usersPath . '/' . $subdomain
        ]);
    }

    /**
     * Provisionne l'utilisateur après paiement
     * Crée le dossier + envoie email de bienvenue
     */
    public function provisionUser($user) {
        try {
            // Génère un identifiant unique à partir du nom de l'association
            $subdomain = strtolower(preg_replace('/[^a-z0-9]/', '', $user->association));
            if (empty($subdomain)) {
                $subdomain = 'user_' . $user->id;
            }

            // Crée le dossier via UAPI
            $result = $this->createUserFolder($subdomain);

            if ($result && isset($result['result']) && $result['result'][0]['result']) {
                // Dossier créé avec succès
                EmailService::sendWelcome($user->email, $user->association);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            // Log l'erreur (à implémenter avec un service de logging)
            error_log('Erreur provisioning cPanel: ' . $e->getMessage());
            return false;
        }
    }
}
