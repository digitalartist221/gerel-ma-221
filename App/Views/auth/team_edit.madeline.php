@use('layout')

@def('content')
<div class="space-y-12 animate-in fade-in duration-700">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-[#050510] tracking-tight mb-2">
            {{ $member ? 'Modifier Collaborateur' : 'Nouveau Collaborateur' }}
        </h1>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Ajoutez un membre à votre organisation avec des accès restreints.</p>
    </div>

    <div class="floating-card p-12 rounded-[3rem] bg-white border-gray-100 shadow-[0_40px_100px_rgba(0,0,0,0.03)]">
        <form action="/equipe/save" method="POST" class="space-y-10">
            @csrf
            @ndax($member)
                <input type="hidden" name="id" value="{{ $member->id }}">
            @jeexndax

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Nom Complet</label>
                    <input type="text" name="name" value="{{ $member->name ?? '' }}" required placeholder="Ex: Moussa Diop" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Email Professionnel</label>
                    <input type="email" name="email" value="{{ $member->email ?? '' }}" required placeholder="moussa@entreprise.com" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
                <div class="space-y-3 md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Rôle & Permissions</label>
                    <select name="role" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 appearance-none">
                        <option value="commercial" {{ ($member && $member->role == 'commercial') ? 'selected' : '' }}>📊 Commercial — Ventes, Documents, Clients</option>
                        <option value="comptable"  {{ ($member && $member->role == 'comptable')  ? 'selected' : '' }}>📒 Comptable — Caisse, Fiscalité, Rapports</option>
                        <option value="admin"      {{ ($member && $member->role == 'admin')      ? 'selected' : '' }}>👑 Administrateur — Accès complet</option>
                        <option value="member"     {{ ($member && $member->role == 'member')     ? 'selected' : '' }}>👤 Membre — Accès minimal (tableau de bord)</option>
                    </select>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest px-2 mt-2">Le rôle définit les modules accessibles pour cet utilisateur.</p>
                </div>
            </div>

            <div class="pt-10 border-t border-gray-50">
                <div class="space-y-3 max-w-md">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Mot de Passe par défaut</label>
                    <input type="password" name="password" {{ $member ? '' : 'required' }} placeholder="Minimum 8 caractères" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                    <p class="text-[9px] text-gray-300 font-bold uppercase tracking-widest px-2">Le collaborateur pourra le modifier dans son profil.</p>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row justify-end gap-3">
                <a href="javascript:history.back()" class="px-12 py-5 rounded-full bg-slate-50 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:bg-slate-100 transition-all text-center">
                    Annuler
                </a>
                <button type="submit" class="px-12 py-5 rounded-full btn-dark text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-black/10 transition-all">
                    {{ $member ? 'Sauvegarder' : 'Créer le compte' }} ↗
                </button>
            </div>
        </form>
    </div>
</div>
@jeexdef
