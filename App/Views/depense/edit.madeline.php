@use('layout')

@def('content')
<div class="max-w-4xl mx-auto py-12">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-[#050510] tracking-tight mb-2">
            {{ $depense ? 'Modifier la Dépense' : 'Enregistrer une Dépense' }}
        </h1>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Détaillez vos sorties de trésorerie pour un bilan précis.</p>
    </div>

    <div class="floating-card p-12 rounded-[3rem] bg-white border-gray-100 shadow-[0_40px_100px_rgba(0,0,0,0.03)]">
        <form action="/depenses/save" method="POST" class="space-y-10">
            @csrf
            @ndax($depense)
                <input type="hidden" name="id" value="{{ $depense->id }}">
            @jeexndax

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Libellé de la dépense</label>
                    <input type="text" name="titre" value="{{ $depense->titre ?? '' }}" required placeholder="Ex: Loyer Bureaux" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-red-500 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Montant (XOF)</label>
                    <input type="number" name="montant" value="{{ $depense->montant ?? '' }}" required placeholder="0" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-red-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Catégorie</label>
                    <select name="categorie" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-red-500 transition-all appearance-none">
                        <option value="loyer" {{ ($depense->categorie ?? '') === 'loyer' ? 'selected' : '' }}>Loyer & Charges</option>
                        <option value="salaires" {{ ($depense->categorie ?? '') === 'salaires' ? 'selected' : '' }}>Salaires</option>
                        <option value="marketing" {{ ($depense->categorie ?? '') === 'marketing' ? 'selected' : '' }}>Marketing & Pub</option>
                        <option value="materiel" {{ ($depense->categorie ?? '') === 'materiel' ? 'selected' : '' }}>Matériel & Stock</option>
                        <option value="autre" {{ ($depense->categorie ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Date</label>
                    <input type="date" name="date_depense" value="{{ $depense->date_depense ?? date('Y-m-d') }}" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-red-500 transition-all">
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Concerne l'entreprise</label>
                <select name="entreprise_id" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-red-500 transition-all appearance-none">
                    @mboloo($entreprises as $ent)
                        <option value="{{ $ent->id }}" {{ ($depense->entreprise_id ?? '') == $ent->id ? 'selected' : '' }}>{{ $ent->nom }}</option>
                    @jeexmboloo
                </select>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="px-12 py-5 rounded-full bg-red-500 text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-red-500/20">
                    Enregistrer la Dépense ↗
                </button>
            </div>
        </form>
    </div>
</div>
@jeexdef
