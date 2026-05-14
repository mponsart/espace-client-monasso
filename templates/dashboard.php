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
        </nav>
    </header>
    <main class="flex-1 max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Bienvenue, <?= htmlspecialchars($user->association) ?></h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
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
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                <div class="mb-2 text-gray-600">Factures</div>
                <?php if ($payments): ?>
                    <ul class="text-sm">
                        <?php foreach ($payments as $p): ?>
                            <li class="mb-1">
                                <span class="font-medium">#<?= htmlspecialchars($p->stripe_payment_id) ?></span> — <?= number_format($p->amount,2,',',' ') ?> € — <?= ucfirst($p->status) ?>
                                <?php if ($p->paid_at): ?>
                                    <span class="text-gray-400 ml-2">(<?= date('d/m/Y', strtotime($p->paid_at)) ?>)</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-gray-400">Aucune facture</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-8">
            <div class="mb-2 text-gray-600">Notifications</div>
            <?php if ($notifications): ?>
                <ul class="text-sm">
                    <?php foreach ($notifications as $n): ?>
                        <li class="mb-1 <?= $n->is_read ? 'text-gray-400' : 'text-indigo-700 font-semibold' ?>">
                            <?= htmlspecialchars($n->message) ?>
                            <span class="text-xs text-gray-400 ml-2">(<?= date('d/m/Y H:i', strtotime($n->created_at)) ?>)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="text-gray-400">Aucune notification</div>
            <?php endif; ?>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-8">
            <h3 class="text-lg font-semibold mb-4">Données personnelles (RGPD)</h3>
            <div class="flex gap-4">
                <a href="/rgpd/export" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Exporter mes données</a>
                <form method="POST" action="/rgpd/delete" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.')">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">Supprimer mon compte</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="mailto:support@monasso.eu" class="text-indigo-600 hover:underline">Contacter le support</a>
        </div>
    </main>
    <footer class="bg-white text-center text-gray-400 text-sm p-4">&copy; 2026 MonAsso. Tous droits réservés.</footer>
</body>
</html>
