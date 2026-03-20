@indi('layout')

@def('pageTitle')Journal de Caisse — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700">
    <!-- Header with Balance & Fiscal Analysis -->
    <header class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 p-6 md:p-12 rounded-[2.5rem] md:rounded-[3.5rem] bg-slate-900 shadow-2xl shadow-black/40 relative overflow-hidden group notebook-edge">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-500 mb-2">Analyse Fiscale & Flux</p>
                        <h1 class="text-5xl font-black text-white tracking-tighter">Journal de Caisse<span class="text-amber-500">.</span></h1>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-500 mb-2 text-right">Solde Actuel</p>
                        <h2 class="text-4xl font-black text-white tracking-tighter">{{ number_format($balance, 0, ',', ' ') }} <span class="text-[10px] font-normal opacity-40">XOF</span></h2>
                    </div>
                </div>
                
                <div class="flex items-center gap-12">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-2">Flux Entrants</p>
                        <p class="text-2xl font-black text-emerald-400">+{{ number_format($totalEntrees, 0, ',', ' ') }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-2">Flux Sortants</p>
                        <p class="text-2xl font-black text-amber-400">-{{ number_format($totalSorties, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-10 rounded-[3.5rem] bg-white border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Actions<br>Comptables.</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-4">Saisie Manuelle & Rapports</p>
            </div>
            <div class="space-y-4">
                <a href="/caisse/nouveau" class="flex items-center justify-center gap-3 w-full py-5 rounded-full bg-amber-500 text-amber-950 text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/10">
                    + Nouveau Mouvement
                </a>
                <a href="/caisse/rapport" class="flex items-center justify-center gap-3 w-full py-5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                    <span>Analyses Fiscales</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Journal Table -->
    <div class="p-12 rounded-[3rem] bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-xl font-black text-slate-900 tracking-tight">Journal des Opérations</h2>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Temps réel</span>
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Horodatage</th>
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Libellé / Secteur</th>
                        <th class="pb-8 text-right text-[11px] font-black uppercase tracking-widest text-slate-300">Volume (XOF)</th>
                        <th class="pb-8 text-right text-[11px] font-black uppercase tracking-widest text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-900">
                    @baat($mouvements as $m)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-8">
                            <p class="text-sm font-black">{{ date('d M Y', strtotime($m->date_mouvement)) }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase">{{ date('H:i', strtotime($m->created_at)) }}</p>
                        </td>
                        <td class="py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-2 h-10 rounded-full {{ $m->type === 'entree' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                <div>
                                    <p class="text-sm font-black">{{ $m->libelle }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $m->categorie }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-8 text-right">
                            <div class="text-sm font-black {{ $m->type === 'entree' ? 'text-emerald-500' : 'text-slate-900' }}">
                                {{ $m->type === 'entree' ? '+' : '-' }} {{ number_format($m->montant, 0, ',', ' ') }}
                            </div>
                            @ndax($m->tva_portion > 0)
                                <p class="text-[9px] font-bold text-slate-300 uppercase">Dont TVA: {{ number_format($m->tva_portion, 0) }}</p>
                            @jeexndax
                        </td>
                        <td class="py-8 text-right">
                            <a href="/caisse/delete/{{ $m->id }}" class="p-3 rounded-xl bg-slate-50 text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </td>
                    </tr>
                    @jeexbaat
                </tbody>
            </table>
        </div>

        @ndax(empty($mouvements))
        <div class="py-32 text-center flex flex-col items-center gap-6">
            <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Flux inertes.</p>
        </div>
        @jeexndax
    </div>
</div>
@jeexdef
