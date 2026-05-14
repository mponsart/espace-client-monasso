<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - MonAsso</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-8 mt-12">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700">Créer un compte</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form method="post" action="/register">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Security::generateCsrfToken()) ?>">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nom de l'association</label>
                <input type="text" name="association" class="w-full border rounded px-3 py-2" required autofocus>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Mot de passe</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                <p class="text-xs text-gray-500 mt-1">Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre</p>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded font-semibold hover:bg-indigo-700 transition">Créer mon compte</button>
        </form>
        <div class="mt-4 text-center text-sm">
            <a href="/login" class="text-indigo-600 hover:underline">Déjà inscrit ? Se connecter</a>
        </div>
    </div>
</body>
</html>
