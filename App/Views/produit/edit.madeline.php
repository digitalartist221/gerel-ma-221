@indi('layout')

@def('pageTitle')Gestion Inventaire — Madeline@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <header class="space-y-3">
        <a href="/produits" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300 hover:text-brand-600 transition-colors flex items-center gap-2">
            <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            <span>Retour au Catalogue</span>
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter">
            {{ $produit ? 'Révision Article.' : 'Nouvelle Référence.' }}
        </h1>
    </header>

    <form action="/produits/save" method="POST" class="space-y-10">
        @csrf
        @ndax($produit)
            <input type="hidden" name="id" value="{{ $produit->id }}">
        @jeexndax

        <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-sm space-y-12 text-slate-900">
            <div class="space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Désignation Commerciale / Nom</label>
                <input type="text" name="designation" value="{{ $produit->designation ?? '' }}" required placeholder="ex: Prestation de Consulting Senior" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Prix de Vente Unitaire (XOF)</label>
                    <input type="number" name="prix_unitaire" value="{{ $produit->prix_unitaire ?? '' }}" required placeholder="0" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Stock Initial / Actuel</label>
                    <input type="number" name="stock" value="{{ $produit->stock ?? 0 }}" required class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">TVA Applicable (%)</label>
                    <input type="number" name="tva" value="{{ $produit->tva ?? 18 }}" required class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
                 <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Entité Gestionnaire</label>
                    <select name="entreprise_id" required class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-6 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all outline-none appearance-none cursor-pointer">
                        @baat($entreprises as $ent)
                            <option value="{{ $ent->id }}" {{ ($produit && $produit->entreprise_id == $ent->id) || (!$produit && ($_SESSION['active_entreprise_id'] ?? 'all') == $ent->id) ? 'selected' : '' }}>{{ $ent->nom }}</option>
                        @jeexbaat
                    </select>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                <a href="javascript:history.back()" class="w-full md:w-auto px-10 py-7 rounded-full bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all text-center">
                    Annuler
                </a>
                <button type="submit" class="w-full flex-1 bg-slate-900 text-white rounded-full py-7 text-xs font-black uppercase tracking-widest shadow-2xl shadow-slate-900/10 hover:bg-brand-600 transition-all flex items-center justify-center gap-3">
                    <span>{{ $produit ? 'Sauvegarder les modifications' : 'Ajouter au catalogue' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>
    </form>
</div>
@jeexdef
