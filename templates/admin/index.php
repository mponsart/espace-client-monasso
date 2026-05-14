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
    <header class="bg-white shadow p-4 flex items-center justify-between">
        <span class="text-xl font-bold text-indigo-700">MonAsso Admin</span>
        <nav>
            <a href="/admin" class="text-indigo-600 hover:underline mx-2">Utilisateurs</a>
            <a href="/logout" class="text-indigo-600 hover:underline mx-2">Déconnexion</a>
        </nav>
    </header>
    <main class="flex-1 max-w-5xl mx-auto py-8 w-full">
        <h1 class="text-3xl font-bold mb-6">Administration des utilisateurs</h1>
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
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
    <footer class="bg-white text-center text-gray-400 text-sm p-4">&copy; 2026 MonAsso. Tous droits réservés.</footer>
</body>
</html>
