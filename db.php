#!/usr/bin/env php
<?php
/**
 * Script rapide de gestion de base de données MonAsso
 * 
 * Utilisation :
 *   php db.php migrate    → Exécute les migrations
 *   php db.php reset      → Reset + migrate
 *   php db.php seed       → Crée un user admin test
 *   php db.php status     → État de la BDD
 */

require_once __DIR__ . '/src/EnvLoader.php';
EnvLoader::load(__DIR__ . '/.env');

$env = [
    'host' => getenv('DB_HOST'),
    'dbname' => getenv('DB_DATABASE'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
];

echo "=== MonAsso DB Manager ===\n";
echo "Host: {$env['host']}\n";
echo "Database: {$env['dbname']}\n";
echo "Username: {$env['username']}\n\n";

$action = $argv[1] ?? 'help';

switch ($action) {
    case 'migrate':
        echo "Exécution des migrations...\n";
        $sqlFiles = glob(__DIR__ . '/migrations/*.sql');
        sort($sqlFiles);
        
        try {
            $pdo = new PDO(
                "mysql:host={$env['host']};dbname={$env['dbname']};charset=utf8mb4",
                $env['username'],
                $env['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            foreach ($sqlFiles as $file) {
                $filename = basename($file);
                echo "  → {$filename}\n";
                $sql = file_get_contents($file);
                $pdo->exec($sql);
            }
            
            echo "\n✅ Migrations terminées avec succès !\n";
        } catch (PDOException $e) {
            echo "\n❌ Erreur : " . $e->getMessage() . "\n";
            exit(1);
        }
        break;

    case 'reset':
        echo "⚠️  Reset complet de la base de données...\n";
        echo "Confirmer (yes/no) : ";
        $handle = fopen("php://stdin", "r");
        $line = trim(fgets($handle));
        fclose($handle);
        
        if ($line !== 'yes') {
            echo "Annulé.\n";
            exit(0);
        }
        
        try {
            $pdo = new PDO(
                "mysql:host={$env['host']};dbname={$env['dbname']};charset=utf8mb4",
                $env['username'],
                $env['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Drop tables
            $tables = ['rgpd_consents', 'notifications', 'payments', 'subscriptions', 'users'];
            foreach ($tables as $table) {
                echo "  → Drop table: {$table}\n";
                $pdo->exec("DROP TABLE IF EXISTS {$table}");
            }
            
            echo "\n✅ Base reset\n";
            
            // Migrate
            echo "\nExécution des migrations...\n";
            $sqlFiles = glob(__DIR__ . '/migrations/*.sql');
            sort($sqlFiles);
            
            foreach ($sqlFiles as $file) {
                $filename = basename($file);
                echo "  → {$filename}\n";
                $sql = file_get_contents($file);
                $pdo->exec($sql);
            }
            
            echo "\n✅ Migrations terminées !\n";
        } catch (PDOException $e) {
            echo "\n❌ Erreur : " . $e->getMessage() . "\n";
            exit(1);
        }
        break;

    case 'seed':
        echo "Création d'un utilisateur admin test...\n";
        
        try {
            $pdo = new PDO(
                "mysql:host={$env['host']};dbname={$env['dbname']};charset=utf8mb4",
                $env['username'],
                $env['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $email = 'admin@monasso.test';
            $password = password_hash('Admin123!', PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (association, email, password, is_admin) VALUES (?, ?, ?, 1)");
            $stmt->execute(['MonAsso Admin', $email, $password]);
            
            echo "\n✅ User admin créé :\n";
            echo "   Email: {$email}\n";
            echo "   Password: Admin123!\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                echo "⚠️  User admin déjà existant\n";
            } else {
                echo "\n❌ Erreur : " . $e->getMessage() . "\n";
                exit(1);
            }
        }
        break;

    case 'status':
        try {
            $pdo = new PDO(
                "mysql:host={$env['host']};dbname={$env['dbname']};charset=utf8mb4",
                $env['username'],
                $env['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            echo "Tables dans la base :\n";
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($tables)) {
                echo "  (aucune table)\n";
            } else {
                foreach ($tables as $table) {
                    $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
                    $count = $stmt->fetchColumn();
                    echo "  ✓ {$table} ({$count} lignes)\n";
                }
            }
        } catch (PDOException $e) {
            echo "\n❌ Erreur de connexion : " . $e->getMessage() . "\n";
            exit(1);
        }
        break;

    case 'help':
    default:
        echo "Commandes disponibles :\n";
        echo "  php db.php migrate   → Exécute les migrations\n";
        echo "  php db.php reset     → Reset + migrate (⚠️ efface tout)\n";
        echo "  php db.php seed      → Crée un user admin test\n";
        echo "  php db.php status    → État de la BDD\n";
        echo "\n";
        break;
}
