@indi('layout')

@def('pageTitle')Réinitialisation du mot de passe — Maye@jeexdef

@def('content')
<div class="min-h-screen flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-md space-y-8">
        <div class="text-center space-y-3">
            <div class="w-16 h-16 rounded-[1.5rem] bg-slate-900 flex items-center justify-center text-white font-black text-xl mx-auto shadow-2xl shadow-slate-900/10">M</div>
            <h1 class="text-2xl font-black text-slate-900">Nouveau mot de passe</h1>
            <p class="text-sm text-slate-400 font-medium">Choisissez un mot de passe sécurisé d'au moins 8 caractères.</p>
        </div>

        @ndax(isset($error))
        <div class="px-6 py-4 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-sm font-medium">
            {{ $error }}
        </div>
        @jeexndax

        <form action="/reset-password/{{ $token }}" method="POST" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="block text-[10px] text-slate-400 font-black uppercase tracking-widest ml-5">Nouveau mot de passe</label>
                <input
                    type="password"
                    name="password"
                    required
                    minlength="8"
                    placeholder="••••••••"
                    class="w-full bg-white border border-slate-100 rounded-[2rem] px-8 py-5 text-base font-bold text-slate-900 focus:ring-4 focus:ring-slate-900/5 outline-none transition-all shadow-sm"
                >
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] text-slate-400 font-black uppercase tracking-widest ml-5">Confirmer le mot de passe</label>
                <input
                    type="password"
                    name="password_confirm"
                    required
                    minlength="8"
                    placeholder="••••••••"
                    class="w-full bg-white border border-slate-100 rounded-[2rem] px-8 py-5 text-base font-bold text-slate-900 focus:ring-4 focus:ring-slate-900/5 outline-none transition-all shadow-sm"
                >
            </div>
            <button type="submit" class="w-full py-5 rounded-[2rem] bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10">
                Enregistrer le nouveau mot de passe
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 font-medium">
            <a href="/login" class="font-black text-slate-900 hover:underline">Retour à la connexion</a>
        </p>
    </div>
</div>
@jeexdef
