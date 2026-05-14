<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonAsso - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span class="text-2xl font-extrabold text-indigo-700 tracking-tight">MonAsso</span>
            </div>
            <nav class="space-x-4">
                <a href="/login" class="text-indigo-600 hover:text-indigo-800 font-medium">Connexion</a>
                <a href="/register" class="text-indigo-600 hover:text-indigo-800 font-medium">Créer un compte</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-4">
        <div class="max-w-2xl mx-auto py-16">
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4V6a5 5 0 00-9.9-1M12 3v1m0 0a5 5 0 00-5 5v4m10 0V8a5 5 0 00-5-5" /></svg>
            </div>
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Bienvenue sur <span class="text-indigo-600">MonAsso</span></h1>
            <p class="text-xl text-gray-600 mb-8">La plateforme SaaS moderne, simple et puissante pour gérer votre association.</p>
            <a href="/register" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-lg font-bold text-lg shadow-lg hover:bg-indigo-700 transition">Créer mon espace</a>
        </div>

        <!-- Avantages -->
        <section class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 py-12">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4V7a4 4 0 10-8 0v6a4 4 0 008 0z" /></svg>
                <h3 class="font-bold text-lg mb-2">Gestion simplifiée</h3>
                <p class="text-gray-500">Centralisez vos membres, cotisations et documents en quelques clics.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 0a8 8 0 018 8v4a8 8 0 01-8 8 8 8 0 01-8-8v-4a8 8 0 018-8z" /></svg>
                <h3 class="font-bold text-lg mb-2">Paiement sécurisé</h3>
                <p class="text-gray-500">Encaissez les cotisations et paiements en ligne grâce à Stripe.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 0h-1v4h-1m-4 0h-1v-4h-1" /></svg>
                <h3 class="font-bold text-lg mb-2">Automatisation</h3>
                <p class="text-gray-500">Automatisez les relances, emails et tâches administratives.</p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-400 text-sm">
            &copy; 2026 MonAsso. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
