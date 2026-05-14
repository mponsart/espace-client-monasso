<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace - MonAsso</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Titillium Web',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow p-4 flex items-center justify-between">
        <span class="text-xl font-bold text-indigo-700">MonAsso</span>
        <nav>
            <a href="/dashboard" class="text-indigo-600 hover:underline mx-2">Dashboard</a>
            <a href="/faq" class="text-indigo-600 hover:underline mx-2">FAQ</a>
            <a href="/logout" class="text-indigo-600 hover:underline mx-2">Déconnexion</a>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mon espace - MonAsso</title>
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
                        <a href="/dashboard" class="text-indigo-600 hover:text-indigo-800 font-medium">Dashboard</a>
                        <a href="/faq" class="text-indigo-600 hover:text-indigo-800 font-medium">FAQ</a>
                        <a href="/logout" class="text-indigo-600 hover:text-indigo-800 font-medium">Déconnexion</a>
                    </nav>
                </div>
            </header>

            <!-- Main -->
            <main class="flex-1 max-w-4xl mx-auto py-12 w-full px-4">
                <h1 class="text-4xl font-extrabold mb-8 text-center">Bienvenue, <span class="text-indigo-600"><?= htmlspecialchars($user->association) ?></span></h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <!-- Abonnement -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4V7a4 4 0 10-8 0v6a4 4 0 008 0z" /></svg>
                        <div class="mb-2 text-gray-600">Abonnement</div>
                        <div class="text-2xl font-bold text-indigo-700">
                            <?= $subscription ? ucfirst($subscription->status) : 'Aucun' ?>
                        </div>
                        <?php if ($subscription): ?>
                            <div class="text-sm text-gray-500 mt-2">Renouvellement : <?= date('d/m/Y', strtotime($subscription->current_period_end)) ?></div>
                            <?php if ($subscription->status === 'suspended'): ?>
                                <?php
                                // Récupère la raison de suspension depuis Stripe (metadata)
                                $reason = null;
                                if ($subscription->stripe_subscription_id) {
                                    $ch = curl_init('https://api.stripe.com/v1/subscriptions/' . $subscription->stripe_subscription_id);
                                    curl_setopt($ch, CURLOPT_USERPWD, $app['stripe']['secret_key'] . ':');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $resp = curl_exec($ch);
                                    curl_close($ch);
                                    $stripeSub = json_decode($resp, true);
                                    $reason = $stripeSub['metadata']['suspension_reason'] ?? null;
                                }
                                ?>
                                <?php if ($reason): ?>
                                    <div class="text-red-600 text-sm mt-2">Raison de la suspension : <span class="font-semibold"><?= htmlspecialchars($reason) ?></span></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <!-- Factures -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 0a8 8 0 018 8v4a8 8 0 01-8 8 8 8 0 01-8-8v-4a8 8 0 018-8z" /></svg>
                        <div class="mb-2 text-gray-600">Factures</div>
                        <?php if ($payments): ?>
                            <ul class="text-sm w-full">
                                <?php foreach ($payments as $p): ?>
                                    <li class="mb-1 flex justify-between items-center border-b border-gray-100 py-1">
                                        <span class="font-medium">#<?= htmlspecialchars($p->stripe_payment_id) ?></span>
                                        <span><?= number_format($p->amount,2,',',' ') ?> €</span>
                                        <span><?= ucfirst($p->status) ?></span>
                                        <?php if ($p->paid_at): ?>
                                            <span class="text-gray-400 ml-2">(<?= date('d/m/Y', strtotime($p->paid_at)) ?>)</span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-gray-400">Aucune facture disponible.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 mt-8">
                    <a href="/faq" class="flex-1 bg-indigo-50 text-indigo-700 px-6 py-4 rounded-lg font-semibold shadow hover:bg-indigo-100 transition text-center">Consulter la FAQ</a>
                    <a href="/rgpd/export" class="flex-1 bg-gray-100 text-gray-700 px-6 py-4 rounded-lg font-semibold shadow hover:bg-gray-200 transition text-center">Exporter mes données (RGPD)</a>
                    <a href="/logout" class="flex-1 bg-red-100 text-red-700 px-6 py-4 rounded-lg font-semibold shadow hover:bg-red-200 transition text-center">Déconnexion</a>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t mt-8">
                <div class="container mx-auto px-4 py-6 text-center text-gray-400 text-sm">
                    &copy; 2026 MonAsso. Tous droits réservés.
                </div>
            </footer>
        </body>
        </html>
        </div>
    </main>
    <footer class="bg-white text-center text-gray-400 text-sm p-4">&copy; 2026 MonAsso. Tous droits réservés.</footer>
</body>
</html>
