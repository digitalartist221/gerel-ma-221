@indi('layout')

@def('pageTitle')Action de Caisse — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <header class="space-y-3">
        <a href="/caisse" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300 hover:text-brand-600 transition-colors flex items-center gap-2">
            <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            <span>Retour au Journal</span>
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter">
            {{ $mouvement ? 'Ajustement Flux.' : 'Nouvelle Écriture.' }}
        </h1>
    </header>

    <form action="/caisse/save" method="POST" class="space-y-10">
        @csrf
        @ndax($mouvement)
            <input type="hidden" name="id" value="{{ $mouvement->id }}">
        @jeexndax

        <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-sm space-y-12 text-slate-900">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Nature du Flux</label>
                    <div class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-50">
                        <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-[9px] font-black uppercase tracking-widest transition-all has-[:checked]:bg-white has-[:checked]:text-brand-600 has-[:checked]:shadow-sm">
                            <input type="radio" name="type" value="entree" class="hidden" {{ (!$mouvement || $mouvement->type == 'entree') ? 'checked' : '' }}> Entrée (+)
                        </label>
                        <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-[9px] font-black uppercase tracking-widest transition-all has-[:checked]:bg-white has-[:checked]:text-red-500 has-[:checked]:shadow-sm">
                            <input type="radio" name="type" value="sortie" class="hidden" {{ ($mouvement && $mouvement->type == 'sortie') ? 'checked' : '' }}> Sortie (-)
                        </label>
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Montant Global (XOF)</label>
                    <input type="number" name="montant" value="{{ $mouvement->montant ?? '' }}" required placeholder="0" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Libellé / Description de l'opération</label>
                <input type="text" name="libelle" value="{{ $mouvement->libelle ?? '' }}" required placeholder="ex: Carburant, Loyer, Vente Directe..." class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Catégorie Comptable</label>
                    <select name="categorie" class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-6 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all outline-none appearance-none cursor-pointer">
                        <option value="Vente" {{ ($mouvement && $mouvement->categorie == 'Vente') ? 'selected' : '' }}>Vente / Chiffre d'Affaires</option>
                        <option value="Achat" {{ ($mouvement && $mouvement->categorie == 'Achat') ? 'selected' : '' }}>Achat / Stock</option>
                        <option value="Charge" {{ ($mouvement && $mouvement->categorie == 'Charge') ? 'selected' : '' }}>Charge de structure</option>
                        <option value="Salaire" {{ ($mouvement && $mouvement->categorie == 'Salaire') ? 'selected' : '' }}>Salaire & Social</option>
                        <option value="Autre" {{ (!$mouvement || $mouvement->categorie == 'Autre') ? 'selected' : '' }}>Autre Flux</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Date d'opération</label>
                    <input type="date" name="date_mouvement" value="{{ $mouvement->date_mouvement ?? date('Y-m-d') }}" class="w-full px-8 py-6 rounded-3xl bg-slate-50 border border-slate-50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 transition-all text-sm font-black outline-none">
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50">
                <button type="submit" class="w-full bg-slate-900 text-white rounded-full py-7 text-xs font-black uppercase tracking-widest shadow-2xl shadow-slate-900/10 hover:bg-brand-600 transition-all flex items-center justify-center gap-3">
                    <span>{{ $mouvement ? 'Mettre à jour le flux' : 'Enregistrer la transaction' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
    </div>
</div>
@jeexdef
