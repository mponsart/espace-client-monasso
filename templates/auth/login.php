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
<body class="bg-gray-50 min-h-screen flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-8 mt-12">
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
</body>
</html>
