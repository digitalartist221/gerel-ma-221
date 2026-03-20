@indi('layout')

@def('pageTitle')Rapport Fiscal — Madeline@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Bilan <span class="text-brand-600">Fiscal.</span></h1>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-300">
                <a href="/caisse" class="hover:text-brand-600 transition-colors">Trésorerie</a>
                <span>/</span>
                <span class="text-slate-400 font-black italic">Consolidé</span>
            </nav>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-6 py-3 rounded-2xl bg-white border border-slate-100 text-slate-900 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                Imprimer Bilan
            </button>
        </div>
    </header>

    <!-- Main Analytical Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 p-10 rounded-[3.5rem] bg-slate-900 text-white shadow-2xl shadow-slate-900/10 flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-brand-400 mb-8">Performance Nette (CA)</p>
                <h2 class="text-7xl font-black tracking-tighter">
                    {{ number_format($stats['ventes_nettes'], 0, ',', ' ') }} <span class="text-sm text-white/30 tracking-widest ml-2">XOF</span>
                </h2>
            </div>
            <div class="mt-12 pt-12 border-t border-white/5 flex gap-12">
                <div class="space-y-1">
                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">Charges Totales</p>
                    <p class="text-xl font-black text-red-400">{{ number_format($stats['charges_totales'], 0, ',', ' ') }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">EBITDA Estimé</p>
                    <p class="text-xl font-black text-emerald-400">{{ number_format($stats['ventes_nettes'] - $stats['charges_totales'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="p-9 rounded-[3rem] bg-brand-50 border border-brand-100">
                <p class="text-[9px] font-black uppercase tracking-widest text-brand-600 opacity-60">TVA Collectée (À Reverser)</p>
                <p class="text-3xl font-black text-brand-900 mt-2">{{ number_format($stats['tva_collectee'], 0, ',', ' ') }} <span class="text-xs">XOF</span></p>
            </div>
            <div class="p-9 rounded-[3rem] bg-orange-50 border border-orange-100">
                <p class="text-[9px] font-black uppercase tracking-widest text-orange-600 opacity-60">BRS/VRS (Withholding)</p>
                <p class="text-3xl font-black text-orange-900 mt-2">{{ number_format($stats['brs_collecte'], 0, ',', ' ') }} <span class="text-xs">XOF</span></p>
            </div>
        </div>
    </div>

    <!-- Breakdown Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 mt-12 bg-white p-10 rounded-[3.5rem] border border-gray-100">
        <div class="lg:col-span-8 space-y-10">
            <h3 class="text-sm font-black uppercase tracking-widest text-[#050510]">Répartition par Catégorie de Charge</h3>
            <div class="space-y-6">
                @baat($stats['categories'] as $cat => $val)
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-widest">
                            <span class="text-gray-400">{{ $cat }}</span>
                            <span class="text-[#050510]">{{ number_format($val, 0) }} XOF</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-50 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-400" style="width: {{ $stats['charges_totales'] > 0 ? ($val / $stats['charges_totales'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @jeexbaat
                @ndax(empty($stats['categories']))
                    <p class="text-xs text-gray-300 italic py-10 text-center">Aucune charge enregistrée.</p>
                @jeexndax
            </div>
        </div>

        <div class="lg:col-span-4 space-y-8">
            <div class="bg-gray-50 rounded-[3rem] p-9 border border-gray-100 flex flex-col gap-4">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Profit Net Estimé</p>
                <?php $profit = $stats['ventes_nettes'] - $stats['charges_totales']; ?>
                <h3 class="text-4xl font-black {{ $profit >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ number_format($profit, 0, ',', ' ') }}
                </h3>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic mt-2 leading-relaxed">
                    Ce montant correspond au résultat d'exploitation avant impôts sur les sociétés.
                </p>
            </div>
        </div>
    </div>
</div>
@jeexdef
