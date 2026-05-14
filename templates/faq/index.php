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
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span class="text-2xl font-extrabold text-indigo-700 tracking-tight">MonAsso</span>
            </div>
            <nav class="space-x-4">
                <a href="/" class="text-indigo-600 hover:text-indigo-800 font-medium">Accueil</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard" class="text-indigo-600 hover:text-indigo-800 font-medium">Dashboard</a>
                    <a href="/logout" class="text-indigo-600 hover:text-indigo-800 font-medium">Déconnexion</a>
                <?php else: ?>
                    <a href="/login" class="text-indigo-600 hover:text-indigo-800 font-medium">Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="flex-1 max-w-3xl mx-auto py-12 w-full px-4">
        <h1 class="text-4xl font-extrabold mb-8 text-center">Questions fréquentes</h1>
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0V5a4 4 0 00-8 0v2a4 4 0 008 0z" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment créer mon association ?</h2>
                    <p class="text-gray-700 text-sm">Inscrivez-vous depuis la page d'accueil en renseignant le nom de votre association et votre email. Votre espace sera créé automatiquement.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 0a8 8 0 018 8v4a8 8 0 01-8 8 8 8 0 01-8-8v-4a8 8 0 018-8z" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment souscrire un abonnement ?</h2>
                    <p class="text-gray-700 text-sm">Depuis votre dashboard, cliquez sur "Souscrire un abonnement". Vous serez redirigé vers le paiement sécurisé Stripe. Votre espace sera activé automatiquement après confirmation du paiement.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 0h-1v4h-1m-4 0h-1v-4h-1" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Que se passe-t-il en cas d'échec de paiement ?</h2>
                    <p class="text-gray-700 text-sm">Votre abonnement sera suspendu automatiquement. Vous recevrez un email vous informant de la situation. Vous pourrez régulariser depuis votre espace client. La raison de suspension est visible dans votre dashboard.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4V7a4 4 0 10-8 0v6a4 4 0 008 0z" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment accéder à mon espace de stockage ?</h2>
                    <p class="text-gray-700 text-sm">Après activation de votre abonnement, un dossier personnel est créé automatiquement sur notre plateforme cPanel. Vous recevrez un email avec les détails d'accès.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment exporter mes données (RGPD) ?</h2>
                    <p class="text-gray-700 text-sm">Depuis votre dashboard, cliquez sur "Exporter mes données". Vous recevrez un fichier JSON contenant l'ensemble de vos informations personnelles.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment supprimer mon compte ?</h2>
                    <p class="text-gray-700 text-sm">Depuis votre dashboard, cliquez sur "Supprimer mon compte". Votre compte sera anonymisé immédiatement. Cette action est irréversible.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0V5a4 4 0 00-8 0v2a4 4 0 008 0z" /></svg>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-indigo-700">Comment contacter le support ?</h2>
                    <p class="text-gray-700 text-sm">Notre support est disponible uniquement par email à l'adresse <a href="mailto:support@monasso.eu" class="text-indigo-600 hover:underline">support@monasso.eu</a>. Nous répondons sous 48h ouvrées.</p>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-400 text-sm">
            &copy; 2026 MonAsso. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
