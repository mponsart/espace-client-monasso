<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MonAsso</title>
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
                <a href="/register" class="text-indigo-600 hover:text-indigo-800 font-medium">Créer un compte</a>
            </nav>
        </div>
    </header>
    <main class="flex-1 flex flex-col justify-center items-center py-12 px-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-10 mt-8">
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0V5a4 4 0 00-8 0v2a4 4 0 008 0z" /></svg>
            </div>
            <h1 class="text-2xl font-bold mb-6 text-indigo-700">Connexion</h1>
            <form method="post" action="/login">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['_token'] ?? '') ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required autofocus>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded font-semibold hover:bg-indigo-700 transition">Se connecter</button>
            </form>
            <div class="mt-4 text-center text-sm">
                <a href="/register" class="text-indigo-600 hover:underline">Créer un compte</a>
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
