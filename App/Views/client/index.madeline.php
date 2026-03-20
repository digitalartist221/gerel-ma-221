@indi('layout')

@def('pageTitle')CRM — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Page Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-2">Portefeuille <span class="text-rose-500">Clients.</span></h1>
            <p class="text-rose-400 font-bold uppercase tracking-[0.2em] text-[10px]">CRM · Relations · Historique</p>
        </div>
        
        <a href="/clients/nouveau" class="px-8 py-4 rounded-full bg-rose-500 text-white text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-xl shadow-rose-500/10 flex items-center gap-2">
            <span>Nouveau Partenaire</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </header>

    <!-- Clients Table -->
    <div class="p-6 md:p-12 rounded-[2rem] md:rounded-[3rem] bg-white border border-slate-100 shadow-sm overflow-hidden text-slate-900 notebook-edge">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Identité / Localisation</th>
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Coordonnées</th>
                        <th class="pb-8 text-right text-[11px] font-black uppercase tracking-widest text-slate-300">Dossier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @baat($clients as $client)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="window.location='/clients/view/{{ $client->id }}'">
                        <td class="py-8">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-sm font-black text-lg">
                                    {{ substr($client->nom, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $client->nom }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $client->adresse ?: 'Dakar, Sénégal' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-8">
                            <p class="text-sm font-black text-slate-600">{{ $client->email }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $client->telephone ?: '--' }}</p>
                        </td>
                        <td class="py-8 text-right">
                            <a href="/clients/view/{{ $client->id }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-rose-500 transition-colors">
                                <span>Consulter</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </td>
                    </tr>
                    @jeexbaat
                </tbody>
            </table>
        </div>

        @ndax(empty($clients))
        <div class="py-32 text-center flex flex-col items-center gap-6">
            <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Base de données vierge.</p>
        </div>
        @jeexndax
    </div>
</div>
@jeexdef
