@indi('layout')

@def('pageTitle')Fiche Partenaire — Madeline@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <header class="space-y-3">
        <a href="/clients" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300 hover:text-brand-600 transition-colors flex items-center gap-2">
            <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            <span>Retour au Portefeuille</span>
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter">
            {{ $client ? 'Édition Profil.' : 'Nouveau Partenaire.' }}
        </h1>
    </header>

    <form action="/clients/save" method="POST" class="space-y-10">
        @csrf
        @ndax($client)
            <input type="hidden" name="id" value="{{ $client->id }}">
        @jeexndax

        <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-sm space-y-12 text-slate-900">
            <!-- Section: Identité -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Nom complet / Raison Sociale</label>
                    <input type="text" name="nom" value="{{ $client->nom ?? '' }}" required placeholder="ex: Groupe Digital Afrique" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Canal Email Officiel</label>
                    <input type="email" name="email" value="{{ $client->email ?? '' }}" required placeholder="contact@partenaire.sn" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
            </div>

            <!-- Section: Contact & Localisation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Ligne Téléphonique</label>
                    <input type="text" name="telephone" value="{{ $client->telephone ?? '' }}" placeholder="+221 ..." class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Adresse Géographique</label>
                    <input type="text" name="adresse" value="{{ $client->adresse ?? '' }}" placeholder="Dakar, Sénégal" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
            </div>

            <!-- Section: Identifiants Fiscaux -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pb-8 border-b border-slate-50">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Numéro NINEA</label>
                    <input type="text" name="ninea" value="{{ $client->ninea ?? '' }}" placeholder="0000000 2G3" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Registre du Commerce (RC)</label>
                    <input type="text" name="rc" value="{{ $client->rc ?? '' }}" placeholder="SN.DKR.2024.B.000" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
            </div>

            <!-- Section: Entité Gestionnaire -->
            <div class="space-y-3 pb-8 border-b border-slate-50">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Entité Gestionnaire</label>
                <select name="entreprise_id" required class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-6 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all outline-none appearance-none cursor-pointer">
                    @baat($entreprises as $ent)
                        <option value="{{ $ent->id }}" {{ ($client && $client->entreprise_id == $ent->id) || (!$client && ($_SESSION['active_entreprise_id'] ?? 'all') == $ent->id) ? 'selected' : '' }}>{{ $ent->nom }}</option>
                    @jeexbaat
                </select>
            </div>

            <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                <a href="javascript:history.back()" class="w-full md:w-auto px-10 py-7 rounded-full bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all text-center">
                    Annuler
                </a>
                <button type="submit" class="w-full flex-1 bg-slate-900 text-white rounded-full py-7 text-xs font-black uppercase tracking-widest shadow-2xl shadow-slate-900/10 hover:bg-brand-600 transition-all flex items-center justify-center gap-3">
                    <span>{{ $client ? 'Enregistrer les modifications' : 'Créer le dossier client' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>
    </form>
</div>
@jeexdef
