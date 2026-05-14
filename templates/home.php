<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonAsso - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/heroicons@2.0.13/dist/heroicons.min.js"></script>
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow p-4 flex items-center justify-between">
        <span class="text-xl font-bold text-indigo-700">MonAsso</span>
        <nav>
            <a href="/login" class="text-indigo-600 hover:underline mx-2">Connexion</a>
            <a href="/register" class="text-indigo-600 hover:underline mx-2">Créer un compte</a>
        </nav>
    </header>
    <main class="flex-1 flex flex-col items-center justify-center">
        <h1 class="text-4xl font-bold mb-4">Bienvenue sur MonAsso</h1>
        <p class="text-lg text-gray-600 mb-8">La plateforme SaaS moderne pour associations.</p>
        <a href="/register" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-indigo-700 transition">Créer mon espace</a>
    </main>
    <footer class="bg-white text-center text-gray-400 text-sm p-4">&copy; 2026 MonAsso. Tous droits réservés.</footer>
</body>
</html>
