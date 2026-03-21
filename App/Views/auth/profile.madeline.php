@use('layout')

@def('content')
<div class="py-12 space-y-12">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-[#050510] tracking-tight mb-4">Votre Profil</h1>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Gérez vos informations personnelles et votre sécurité.</p>
    </div>

    @ndax(isset($_GET['updated']))
        <div class="mb-8 p-6 bg-green-50 border border-green-100 rounded-3xl text-green-600 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Profil mis à jour avec succès !
        </div>
    @jeexndax

    <div class="floating-card p-12 rounded-[3rem] bg-white border-gray-100 shadow-[0_40px_100px_rgba(0,0,0,0.03)]">
        <form action="/profile/update" method="POST" class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Nom Complet</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Email Professionnel</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
            </div>

            <div class="pt-10 border-t border-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-2xl">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Nouveau Mot de Passe</label>
                        <input type="password" name="password" id="pw1" placeholder="Laisser vide pour ne pas changer" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Confirmer le Mot de Passe</label>
                        <input type="password" name="password_confirm" id="pw2" placeholder="Répétez le mot de passe" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                    </div>
                </div>
                <p class="text-[9px] text-gray-300 font-bold uppercase tracking-widest px-2 mt-3">Sécurité : minimum 8 caractères. Laisser vide pour conserver le mot de passe actuel.</p>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="px-12 py-5 rounded-full btn-dark text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-black/10 inline-flex items-center gap-2">
                    Sauvegarder les modifications
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@jeexdef
