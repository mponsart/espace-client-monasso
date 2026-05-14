<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - MonAsso</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span class="text-2xl font-extrabold text-indigo-700 tracking-tight">MonAsso Admin</span>
            </div>
            <nav class="space-x-4">
                <a href="/admin" class="text-indigo-600 hover:text-indigo-800 font-medium">Utilisateurs</a>
                <a href="/logout" class="text-indigo-600 hover:text-indigo-800 font-medium">Déconnexion</a>
            </nav>
        </div>
    </header>
    <main class="flex-1 max-w-5xl mx-auto py-12 w-full px-4">
        <h1 class="text-4xl font-extrabold mb-8 text-center">Administration des utilisateurs</h1>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscrit le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $u): ?>
                        <?php $sub = Subscription::findByUserId($u->id); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($u->association) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($u->email) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if ($sub): ?>
                                    <span class="px-2 py-1 text-xs rounded-full <?= $sub->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($sub->status) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Aucun</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($u->created_at)) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="/admin/user?id=<?= $u->id ?>" class="text-indigo-600 hover:underline">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-400 text-sm">
            &copy; 2026 MonAsso. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
