<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user->association) ?> - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow p-4 flex items-center justify-between">
        <span class="text-xl font-bold text-indigo-700">MonAsso Admin</span>
        <nav>
            <a href="/admin" class="text-indigo-600 hover:underline mx-2">Retour</a>
            <a href="/logout" class="text-indigo-600 hover:underline mx-2">Déconnexion</a>
        </nav>
    </header>
    <main class="flex-1 max-w-3xl mx-auto py-8 w-full">
        <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($user->association) ?></h1>
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Informations</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Email :</span> <span class="font-medium"><?= htmlspecialchars($user->email) ?></span></div>
                <div><span class="text-gray-500">Inscrit le :</span> <span class="font-medium"><?= date('d/m/Y', strtotime($user->created_at)) ?></span></div>
            </div>
        </div>
        <?php if ($subscription): ?>
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Abonnement</h2>
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div><span class="text-gray-500">Statut :</span>
                        <span class="font-medium px-2 py-1 rounded-full <?= $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= ucfirst($subscription->status) ?>
                        </span>
                    </div>
                    <div><span class="text-gray-500">ID Stripe :</span> <span class="font-mono text-xs"><?= htmlspecialchars($subscription->stripe_subscription_id) ?></span></div>
                </div>
                <?php if ($subscription->status === 'suspended'): ?>
                    <?php
                    $reason = null;
                    if ($subscription->stripe_subscription_id) {
                        $config = require __DIR__ . '/../../../config/stripe.php';
                        $ch = curl_init('https://api.stripe.com/v1/subscriptions/' . $subscription->stripe_subscription_id);
                        curl_setopt($ch, CURLOPT_USERPWD, $config['secret_key'] . ':');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $resp = curl_exec($ch);
                        curl_close($ch);
                        $stripeSub = json_decode($resp, true);
                        $reason = $stripeSub['metadata']['suspension_reason'] ?? null;
                    }
                    ?>
                    <form method="POST" action="/admin/user/update-suspension" class="mt-4">
                        <input type="hidden" name="user_id" value="<?= $user->id ?>">
                        <input type="hidden" name="subscription_id" value="<?= htmlspecialchars($subscription->stripe_subscription_id) ?>">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Raison de suspension (Stripe metadata)</label>
                        <input type="text" name="suspension_reason" value="<?= htmlspecialchars($reason ?? '') ?>" class="w-full border rounded-md px-3 py-2 text-sm mb-2" placeholder="Ex: Paiement échoué, Demande utilisateur...">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Mettre à jour</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Abonnement</h2>
                <div class="text-gray-500 text-sm">Aucun abonnement trouvé</div>
            </div>
        <?php endif; ?>
    </main>
    <footer class="bg-white text-center text-gray-400 text-sm p-4">&copy; 2026 MonAsso. Tous droits réservés.</footer>
</body>
</html>
