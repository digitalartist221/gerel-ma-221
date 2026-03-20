@indi('layout')

@def('pageTitle')Dossier CRM : {{ $client->nom }} — Madeline@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700">
    <!-- Breadcrumb & Actions -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Fiche <span class="text-brand-600">Partenaire.</span></h1>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-300">
                <a href="/clients" class="hover:text-brand-600 transition-colors">Portefeuille</a>
                <span>/</span>
                <span class="text-slate-400 font-black italic">{{ $client->nom }}</span>
            </nav>
        </div>
        
        <div class="flex items-center gap-3">
             <a href="/clients/edit/{{ $client->id }}" class="px-6 py-3 rounded-2xl bg-white border border-slate-100 text-slate-900 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                Éditer le profil
            </a>
            <a href="/documents/nouveau?client_id={{ $client->id }}" class="px-6 py-3 rounded-2xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-brand-600 transition-all shadow-xl shadow-brand-500/10 flex items-center gap-2">
                <span>Nouvelle Facture</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Sidebar: Identity -->
        <div class="lg:col-span-4 space-y-8">
            <div class="p-10 rounded-[3.5rem] bg-slate-900 text-white shadow-2xl shadow-slate-900/10">
                <div class="w-24 h-24 rounded-[2.5rem] bg-brand-600 flex items-center justify-center text-4xl font-black text-white mb-8 shadow-xl shadow-brand-500/20">
                    {{ substr($client->nom, 0, 1) }}
                </div>
                <h2 class="text-3xl font-black mb-4 tracking-tight leading-tight">{{ $client->nom }}</h2>
                <div class="space-y-8 mt-10 opacity-70 border-t border-white/5 pt-10">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black uppercase tracking-widest text-brand-400">Coordonnées Email</p>
                        <p class="text-sm font-bold">{{ $client->email }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black uppercase tracking-widest text-brand-400">Direct Contact</p>
                        <p class="text-sm font-bold">{{ $client->telephone ?: '--' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black uppercase tracking-widest text-brand-400">Siège Social / Localisation</p>
                        <p class="text-sm font-bold leading-relaxed">{{ $client->adresse ?: 'Dakar, Sénégal' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Pulse Cards -->
            <div class="p-10 rounded-[2.5rem] bg-emerald-50 border border-emerald-100 flex items-center justify-between group">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-600 opacity-60">Total Encaissé (LTV)</p>
                    <p class="text-3xl font-black text-emerald-700 mt-1">{{ number_format($totalSpent, 0, ',', ' ') }} <span class="text-xs">XOF</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-emerald-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="p-10 rounded-[2.5rem] bg-orange-50 border border-orange-100 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-orange-600 opacity-60">Encours à Recouvrer</p>
                    <p class="text-3xl font-black text-orange-700 mt-1">{{ number_format($outstandingBalance, 0, ',', ' ') }} <span class="text-xs">XOF</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-orange-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Main Workspace: Activity -->
        <div class="lg:col-span-8 space-y-8">
            <div class="p-12 rounded-[3rem] bg-white border border-slate-100 shadow-sm overflow-hidden text-slate-900">
                <div class="flex items-center justify-between mb-12">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-900">Historique des Flux</h3>
                    <span class="px-4 py-1.5 rounded-full bg-slate-50 text-[9px] font-black uppercase tracking-widest text-slate-300">{{ count($documents) }} documents</span>
                </div>

                @ndax(!empty($documents))
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-50">
                                <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-slate-300">Référence / Objet</th>
                                <th class="pb-6 text-center text-[10px] font-black uppercase tracking-widest text-slate-300">Statut</th>
                                <th class="pb-6 text-right text-[10px] font-black uppercase tracking-widest text-slate-300">Total Net</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @baat($documents as $doc)
                            <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="window.location='/documents/edit/{{ $doc->id }}'">
                                <td class="py-6">
                                    <p class="text-sm font-black text-slate-900">{{ $doc->numero }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $doc->type }} &middot; {{ $doc->date_emission }}</p>
                                </td>
                                <td class="py-6 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest {{ $doc->statut === 'paye' ? 'bg-emerald-100 text-emerald-600' : 'bg-brand-100 text-brand-600' }}">
                                        {{ $doc->statut }}
                                    </span>
                                </td>
                                <td class="py-6 text-right font-black text-slate-900 text-sm">
                                    {{ number_format($doc->total_ttc, 0, ',', ' ') }} <span class="text-[10px] text-slate-300 italic">XOF</span>
                                </td>
                            </tr>
                            @jeexbaat
                        </tbody>
                    </table>
                </div>
                @xaaj
                <div class="py-32 text-center flex flex-col items-center gap-6">
                    <div class="w-16 h-16 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">Aucune activité enregistrée.</p>
                </div>
                @jeexndax
            </div>
        </div>
    </div>
</div>
@jeexdef
