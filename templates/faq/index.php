<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - MonAsso</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow p-4 flex items-center justify-between">
        <span class="text-xl font-bold text-indigo-700">MonAsso</span>
        <nav>
            <a href="/" class="text-indigo-600 hover:underline mx-2">Accueil</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard" class="text-indigo-600 hover:underline mx-2">Dashboard</a>
                <a href="/logout" class="text-indigo-600 hover:underline mx-2">Déconnexion</a>
            <?php else: ?>
                <a href="/login" class="text-indigo-600 hover:underline mx-2">Connexion</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="flex-1 max-w-3xl mx-auto py-8 w-full px-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Questions fréquentes</h1>
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment créer mon association ?</h2>
                <p class="text-gray-700 text-sm">Inscrivez-vous depuis la page d'accueil en renseignant le nom de votre association et votre email. Votre espace sera créé automatiquement.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment souscrire un abonnement ?</h2>
                <p class="text-gray-700 text-sm">Depuis votre dashboard, cliquez sur "Souscrire un abonnement". Vous serez redirigé vers le paiement sécurisé Stripe. Votre espace sera activé automatiquement après confirmation du paiement.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Que se passe-t-il en cas d'échec de paiement ?</h2>
                <p class="text-gray-700 text-sm">Votre abonnement sera suspendu automatiquement. Vous recevrez un email vous informant de la situation. Vous pourrez régulariser depuis votre espace client. La raison de suspension est visible dans votre dashboard.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment accéder à mon espace de stockage ?</h2>
                <p class="text-gray-700 text-sm">Après activation de votre abonnement, un dossier personnel est créé automatiquement sur notre plateforme cPanel. Vous recevrez un email avec les détails d'accès.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment exporter mes données (RGPD) ?</h2>
                <p class="text-gray-700 text-sm">Depuis votre dashboard, cliquez sur "Exporter mes données". Vous recevrez un fichier JSON contenant l'ensemble de vos informations personnelles.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment supprimer mon compte ?</h2>
                <p class="text-gray-700 text-sm">Depuis votre dashboard, cliquez sur "Supprimer mon compte". Votre compte sera anonymisé immédiatement. Cette action est irréversible.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment contacter le support ?</h2>
                <p class="text-gray-700 text-sm">Notre support est disponible uniquement par email à l'adresse <a href="mailto:support@monasso.eu" class="text-indigo-600 hover:underline">support@monasso.eu</a>. Nous répondons sous 48h ouvrées.</p>
            </div>
        </div>
    </main>
    <footer class="bg-white text-center text-gray-400 text-sm p-4">&copy; 2026 MonAsso. Tous droits réservés.</footer>
</body>
</html>
