@indi('layout')

@def('pageTitle')Configuraton Entreprise — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="mb-16">
        <a href="/entreprises" class="text-[10px] font-black uppercase text-gray-400 tracking-widest hover:text-brand-500 transition-colors mb-8 inline-block">← Retour à la liste</a>
        <h1 class="text-5xl font-extrabold text-[#050510] tracking-tight">
            {{ $entreprise ? 'Éditer l\'entité.' : 'Nouvelle entreprise.' }}
        </h1>
    </div>

    <form action="/entreprises/save" method="POST" class="space-y-8">
        @csrf
        @ndax($entreprise)
            <input type="hidden" name="id" value="{{ $entreprise->id }}">
        @jeexndax

        <div class="floating-card p-12 rounded-6xl border-gray-100 space-y-12">
            <!-- Branding -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">Nom de l'entreprise</label>
                    <input type="text" name="nom" value="{{ $entreprise->nom ?? '' }}" required placeholder="ex: Digital Artist Studio" class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-bold">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">Numéro NINEA</label>
                    <input type="text" name="ninea" value="{{ $entreprise->ninea ?? '' }}" placeholder="0000000 2G3" class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-bold tracking-widest">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">Registre du Commerce (RC)</label>
                    <input type="text" name="rc" value="{{ $entreprise->rc ?? '' }}" placeholder="SN.DKR.2024.B.000" class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-bold tracking-widest">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">Site Web / Portfolio</label>
                    <input type="text" name="website" value="{{ $entreprise->website ?? '' }}" placeholder="https://gerelma.com" class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-bold">
                </div>
            </div>

            <!-- Logo URL (Simulation Storage) -->
             <div class="space-y-4">
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">URL du Logo (ou Initiales par défaut)</label>
                <input type="text" name="logo" value="{{ $entreprise->logo ?? '' }}" placeholder="https://..." class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>

            <hr class="border-gray-50">

            <!-- Localisation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">Adresse</label>
                    <input type="text" name="adresse" value="{{ $entreprise->adresse ?? '' }}" placeholder="12 Rue de l'Avenir" class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4">Ville / Pays</label>
                    <input type="text" name="ville" value="{{ $entreprise->ville ?? '' }}" placeholder="Dakar, Sénégal" class="w-full px-8 py-5 rounded-3xl bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
                </div>
            </div>

            <div class="pt-8">
                <button type="submit" class="w-full btn-dark rounded-full py-6 text-[11px] font-bold uppercase tracking-widest shadow-2xl shadow-black/10 inline-flex items-center justify-center gap-2">
                    {{ $entreprise ? 'Mettre à jour l\'entité' : 'Confirmer la création' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>
    </form>
</div>
@jeexdef
