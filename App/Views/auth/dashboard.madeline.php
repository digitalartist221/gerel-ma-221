@use('layout')

@def('content')
<script src="/js/vendor/chart.js"></script>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header Hero -->
    <header class="flex flex-col lg:flex-row justify-between items-center gap-6">
        <!-- Title Block -->
        <div class="shrink-0 self-center">
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-1">Synthèse <span class="text-brand-500">Business.</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">Intelligence financière & Pilotage en temps réel</p>
        </div>

        <!-- Controls Block -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Form : single horizontal pill -->
            <form method="GET" action="/dashboard" id="dashboard-filters"
                  class="flex items-center divide-x divide-slate-100 bg-white border border-slate-100 shadow-sm rounded-[2rem] overflow-hidden">

                <!-- Period Select -->
                <label class="flex items-center gap-2 px-5 py-3.5 hover:bg-slate-50 transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <select name="period" onchange="this.form.submit()"
                            class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest text-slate-700 focus:ring-0 cursor-pointer outline-none pr-2 appearance-none">
                        <option value="this_month"  {{ ($filters['period'] ?? '') === 'this_month'  ? 'selected' : '' }}>Ce mois</option>
                        <option value="last_month"  {{ ($filters['period'] ?? '') === 'last_month'  ? 'selected' : '' }}>Mois précédent</option>
                        <option value="this_year"   {{ ($filters['period'] ?? '') === 'this_year'   ? 'selected' : '' }}>Cette année</option>
                        <option value="all"         {{ ($filters['period'] ?? '') === 'all'         ? 'selected' : '' }}>Tout l'historique</option>
                    </select>
                </label>

                <!-- Divider handled by divide-x above -->

                <!-- Enterprise Select -->
                <label class="flex items-center gap-2 px-5 py-3.5 hover:bg-slate-50 transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <select name="entreprise_id" onchange="this.form.submit()"
                            class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest text-indigo-700 focus:ring-0 cursor-pointer outline-none pr-2 appearance-none">
                        <option value="all">Toutes les entités</option>
                        @baat($entreprises as $ent)
                            <option value="{{ $ent->id }}" {{ ($filters['entreprise_id'] ?? '') == $ent->id ? 'selected' : '' }}>{{ $ent->nom }}</option>
                        @jeexbaat
                    </select>
                </label>
            </form>

            <!-- Fiscal Report CTA -->
            <a href="/dashboard/fiscalite?period={{ $filters['period'] ?? 'this_month' }}&entreprise_id={{ $filters['entreprise_id'] ?? 'all' }}"
               target="_blank" data-no-madeline="true"
               class="flex items-center gap-2 px-6 py-3.5 rounded-[2rem] bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 transition-all shadow-xl shadow-slate-900/10 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Rapport Fiscal
            </a>
        </div>
    </header>

    <div class="grid grid-cols-12 gap-10">
        <!-- Left Column: Chart & Table -->
        <div class="col-span-12 lg:col-span-8 space-y-10">
            <!-- Activity Chart -->
            <div class="p-6 md:p-12 rounded-[2rem] md:rounded-[3.5rem] bg-white border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="floating-deco -right-4 -top-4 text-brand-100 rotate-12 text-6xl opacity-30">✻</div>
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Performances Commerciales</h2>
                        <p class="text-[11px] text-rose-500 font-bold uppercase tracking-widest mt-2">Volume d'affaires mensuel</p>
                    </div>
                </div>
                <div class="h-[350px]">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="p-6 md:p-12 rounded-[2rem] md:rounded-[3.5rem] bg-white border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Flux de Ventes Récents</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-50 border-none rounded-xl text-[10px] font-black uppercase px-4 py-2 text-slate-500">
                            <option>Derniers 30 jours</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-50">
                                <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-slate-300">Document</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-slate-300">Client</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-slate-300">État</th>
                                <th class="pb-6 text-right text-[10px] font-black uppercase tracking-widest text-slate-300">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-900">
                            @mboloo($recentDocs as $doc)
                            <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="window.location='/documents/edit/{{ $doc->id }}'">
                                <td class="py-6">
                                    <p class="text-sm font-black">{{ $doc->type === 'devis' ? 'Cee-mi' : ($doc->type === 'commande' ? 'Ndoggal' : 'Fay-wi') }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $doc->numero }}</p>
                                </td>
                                <td class="py-6">
                                    <p class="text-sm font-bold">{{ $doc->client_name ?? 'Client Divers' }}</p>
                                </td>
                                <td class="py-6">
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase {{ $doc->statut === 'paye' ? 'bg-emerald-100 text-emerald-600' : 'bg-brand-100 text-brand-600' }}">
                                        {{ $doc->statut }}
                                    </span>
                                </td>
                                <td class="py-6 text-right font-black text-sm">{{ number_format($doc->total_ttc, 0, ',', ' ') }} <span class="text-[10px] font-normal opacity-40">XOF</span></td>
                            </tr>
                            @jeexmboloo
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Sprrrint-style KPIs -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <!-- Main Solde -->
            <div class="p-6 md:p-9 rounded-[2rem] md:rounded-[3rem] bg-rose-500 text-white shadow-2xl shadow-rose-500/20 relative overflow-hidden group notebook-edge">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                <!-- Floating Deco -->
                <div class="floating-deco top-4 right-8 text-white/20">✦</div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60 mb-8 relative z-10">Performance Nette (CA)</p>
                <h3 class="text-5xl font-black mb-2 tracking-tighter relative z-10">{{ number_format($bilan, 0, ',', ' ') }}</h3>
                <p class="text-[10px] font-bold opacity-60 relative z-10 uppercase tracking-widest">Global Résolu XOF</p>
                <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest relative z-10">
                    <span class="px-3 py-1 bg-white/20 rounded-lg">Performance +12%</span>
                </div>
            </div>

            <!-- Tax KPI 1: TVA -->
            <div class="p-7 rounded-[2.5rem] bg-amber-400 text-amber-950 border-none shadow-xl shadow-amber-400/10 flex items-center justify-between group hover:scale-[1.02] transition-all notebook-edge">
                <div class="space-y-1">
                    <p class="text-[10px] font-black opacity-60 uppercase tracking-widest">TVA à Reverser</p>
                    <h4 class="text-3xl font-black tracking-tighter">{{ number_format($tva_a_reverser, 0, ',', ' ') }}</h4>
                    <p class="text-[9px] font-bold text-amber-950/70 uppercase tracking-widest">Collecté (18%)</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-amber-950">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>

            <!-- Tax KPI 2: BRS -->
            <div class="p-7 rounded-[2.5rem] bg-white border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-xl transition-all notebook-edge">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">BRS Retenue</p>
                    <h4 class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($brs_collecte, 0, ',', ' ') }}</h4>
                    <p class="text-[9px] font-bold text-brand-500 uppercase tracking-widest">GIE & Prestataires (5%)</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Top Clients Mini Widget -->
            <div class="p-9 rounded-[3rem] bg-slate-900 text-white shadow-2xl shadow-black/20">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-black tracking-tight">Top Clients</h3>
                    <span class="text-[9px] font-black uppercase text-brand-400">Valeur Vie</span>
                </div>
                <div class="space-y-6">
                    @baat($topClients as $tc)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-[10px] font-black text-slate-400 group-hover:bg-brand-500 group-hover:text-white transition-all">
                                {{ strtoupper(substr($tc['name'], 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold text-slate-400 group-hover:text-white transition-colors">{{ $tc['name'] }}</span>
                        </div>
                        <span class="text-xs font-black">{{ number_format($tc['value'], 0, ',', ' ') }}</span>
                    </div>
                    @jeexbaat
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
// Chart Excellence: Évolution Mensuelle
const evoCanvas = document.getElementById('evolutionChart');
if (!evoCanvas) return;

// Destroy previous chart instance if exists (SPA safety)
if (evoCanvas._chartInstance) {
    evoCanvas._chartInstance.destroy();
}

const ctxEvolution = evoCanvas.getContext('2d');
const gradient = ctxEvolution.createLinearGradient(0, 0, 0, 350);
gradient.addColorStop(0, 'rgba(139, 92, 246, 0.2)');
gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');

evoCanvas._chartInstance = new Chart(ctxEvolution, {
    type: 'line',
    data: {
        labels: @json($revenueEvolution['labels']),
        datasets: [{
            label: 'Revenu Mensuel',
            data: @json($revenueEvolution['values']),
            borderColor: '#8b5cf6',
            borderWidth: 5,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#8b5cf6',
            pointBorderWidth: 4,
            pointRadius: 4,
            pointHoverRadius: 8,
            pointHoverBorderWidth: 4,
            tension: 0.4,
            fill: true,
            backgroundColor: gradient,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        scales: {
            y: { 
                grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                ticks: { display: false }
            },
            x: { 
                grid: { display: false },
                ticks: { font: { size: 10, weight: '800' }, color: '#94a3b8', padding: 20 }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 20,
                titleFont: { size: 12, weight: '900', family: 'Outfit' },
                bodyFont: { size: 14, family: 'Outfit' },
                borderWidth: 1,
                borderColor: 'rgba(255,255,255,0.1)',
                displayColors: false,
                callbacks: { label: (c) => ' ' + c.formattedValue + ' XOF' }
            }
        }
    }
});
})();
</script>
@jeexdef
t>
@jeexdef
@jeexdef
