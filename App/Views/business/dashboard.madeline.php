@indi('layout')

@def('pageTitle')Tableau de Bord — Gerel Ma Business@jeexdef

@def('content')
<div class="relative py-12">
    <!-- Hero Section -->
    <div class="text-center mb-24 relative">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-50/50 border border-brand-100 text-brand-600 text-[10px] font-bold uppercase tracking-widest mb-8">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
            </span>
            Système opérationnel
        </div>
        <h1 class="text-7xl font-extrabold text-[#050510] tracking-tight leading-[1.1] mb-8">
            Gérez vos affaires<br>
            <span class="text-gray-400">avec une clarté absolue.</span>
        </h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-12 font-medium">
            Facturation, contrats et CRM unifiés dans une expérience fluide, ultra-minimaliste et pensée pour la croissance de votre entreprise.
        </p>
        <div class="flex items-center justify-center gap-4">
            <button class="px-8 py-4 rounded-full btn-dark text-sm font-bold shadow-2xl shadow-black/10">
                Créer une Facture
            </button>
            <button class="px-8 py-4 rounded-full bg-white border border-gray-100 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-all">
                Démarrer un Contrat
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
        <div class="floating-card p-10 rounded-6xl">
            <div class="flex items-center justify-between mb-8">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500 italic font-black">€</div>
                <div class="text-[10px] font-bold text-green-500 bg-green-50 px-2.5 py-1 rounded-full">+12%</div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Chiffre d'Affaire</div>
            <div class="text-4xl font-extrabold text-[#050510] leading-none mb-1">{{ $stats['ca_mois'] }}</div>
        </div>

        <div class="floating-card p-10 rounded-6xl">
            <div class="flex items-center justify-between mb-8">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 italic font-black">!</div>
                <div class="text-[10px] font-bold text-orange-500 bg-orange-50 px-2.5 py-1 rounded-full">8 Docs</div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Impayés</div>
            <div class="text-4xl font-extrabold text-[#050510] leading-none mb-1">{{ $stats['factures_impayer'] }}</div>
        </div>

        <div class="floating-card p-10 rounded-6xl border-brand-200">
            <div class="flex items-center justify-between mb-8">
                <div class="w-12 h-12 rounded-2xl bg-brand-500 flex items-center justify-center text-white italic font-black">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Taux de Signature</div>
            <div class="text-4xl font-extrabold text-[#050510] leading-none mb-1">{{ $stats['taux_conversion'] }}</div>
        </div>
    </div>

    <!-- Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 floating-card p-12 rounded-[3.5rem]">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-2xl font-black text-[#050510] tracking-tight">Documents Récents</h3>
                <a href="/documents" class="text-[10px] font-black uppercase text-brand-500 tracking-widest hover:underline">Tout voir —</a>
            </div>
            
            <div class="space-y-6">
                @baat($recentDocs as $doc)
                <div class="flex items-center justify-between p-6 rounded-4xl hover:bg-gray-50 transition-all group border border-transparent hover:border-gray-100">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500 font-black text-lg">
                            {{ substr($doc->type, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-[#050510] mb-1 group-hover:text-brand-600 transition-colors">{{ $doc->type }} #{{ $doc->numero }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-medium">Digital Studio CRM</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-base font-black text-[#050510] mb-1">{{ number_format($doc->total_ttc, 0, ',', ' ') }} FCFA</div>
                        <x-invoice-status type="{{ $doc->statut }}" />
                    </div>
                </div>
                @jeexbaat
                @ndax(empty($recentDocs))
                <div class="text-center py-20">
                    <svg class="w-12 h-12 mx-auto mb-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <p class="text-gray-400 text-sm font-medium">Aucun document n'a été créé pour le moment.</p>
                </div>
                @jeexndax
            </div>
        </div>
</div>
@jeexdef
