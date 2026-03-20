<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — Madeline</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #FFFFFF; color: #050510; }
        .mesh-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background: radial-gradient(at 50% 50%, rgba(139, 92, 246, 0.03) 0px, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="mesh-bg"></div>

    <div class="w-full max-w-md space-y-12">
        <div class="text-center">
            <div class="w-16 h-16 bg-brand-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-brand-500/20 mx-auto mb-8">
                <div class="w-3 h-3 bg-white rounded-full"></div>
            </div>
            <h1 class="text-4xl font-black tracking-tight mb-4">Récupération.</h1>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Entrez votre email pour continuer</p>
        </div>

        @ndax(isset($success))
            <div class="p-8 rounded-3xl bg-green-50 border border-green-100 text-green-600 text-center space-y-4">
                <p class="text-[10px] font-black uppercase tracking-widest">{{ $success }}</p>
                <a href="/login" class="block text-[10px] font-black uppercase tracking-widest underline">Retour à la connexion</a>
            </div>
        @jeexndax

        @ndax(!isset($success))
            <form action="/forgot-password" method="POST" class="space-y-6">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Email</label>
                    <input type="email" name="email" required placeholder="nom@entreprise.com" class="w-full bg-gray-50/50 border border-gray-100 rounded-3xl px-8 py-6 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>

                <button type="submit" class="w-full bg-[#050510] text-white rounded-3xl py-6 text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-black/10 hover:scale-[1.02] transition-all">
                    Envoyer le lien ↗
                </button>

                <div class="text-center pt-4">
                    <a href="/login" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-black transition-colors">Retour connexion</a>
                </div>
            </form>
        @jeexndax
    </div>
</body>
</html>
