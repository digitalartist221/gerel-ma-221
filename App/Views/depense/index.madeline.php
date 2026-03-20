@use('layout')

@def('content')
<div class="py-12">
    <!-- Dépenses Header -->
    <div class="relative mb-12 overflow-hidden bg-gradient-to-br from-rose-950 to-red-900 rounded-[2rem] md:rounded-[3.5rem] p-8 md:p-12 flex items-center justify-between">
        <div class="relative z-10">
            <p class="text-[9px] font-black uppercase tracking-[0.4em] text-rose-300 mb-3">Gestion des Charges</p>
            <h1 class="text-5xl font-black text-white tracking-tight mb-3">Dépenses.</h1>
            <p class="text-rose-200/60 font-medium mb-8">Maîtrisez vos flux de sortie · Catégories · Rentabilité</p>
            <a href="/depenses/nouveau" class="inline-flex items-center gap-3 px-7 py-4 rounded-full bg-white text-rose-700 text-[11px] font-black uppercase tracking-widest hover:bg-rose-50 transition-all shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Dépense
            </a>
        </div>
        <!-- SVG trend-down chart -->
        <div class="hidden lg:block absolute right-10 top-1/2 -translate-y-1/2 opacity-10">
            <svg width="200" height="130" viewBox="0 0 200 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polyline points="10,30 50,45 90,35 130,75 170,95 195,110" stroke="white" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="10,30 50,45 90,35 130,75 170,95 195,110 195,125 10,125" fill="white" opacity="0.08"/>
                <circle cx="10"  cy="30"  r="5" fill="white" opacity="0.5"/>
                <circle cx="50"  cy="45"  r="5" fill="white" opacity="0.5"/>
                <circle cx="90"  cy="35"  r="5" fill="white" opacity="0.5"/>
                <circle cx="130" cy="75"  r="5" fill="white" opacity="0.5"/>
                <circle cx="170" cy="95"  r="5" fill="white" opacity="0.5"/>
                <circle cx="195" cy="110" r="5" fill="white" opacity="0.5"/>
            </svg>
        </div>
    </div>

    <div class="floating-card rounded-[3rem] bg-white border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Titre</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Catégorie</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @mboloo($depenses as $depense)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="px-10 py-6">
                        <span class="text-sm font-bold text-[#050510]">{{ $depense->titre }}</span>
                    </td>
                    <td class="px-10 py-6">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-[9px] font-black uppercase tracking-widest text-gray-400">{{ $depense->categorie }}</span>
                    </td>
                    <td class="px-10 py-6">
                        <span class="text-xs font-medium text-gray-400">{{ $depense->date_depense }}</span>
                    </td>
                    <td class="px-10 py-6 text-right">
                        <span class="text-sm font-black text-red-500">{{ number_format($depense->montant, 0, ',', ' ') }} <span class="text-[10px]">XOF</span></span>
                    </td>
                </tr>
                @jeexmboloo
            </tbody>
        </table>
    </div>
</div>
@jeexdef
