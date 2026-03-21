@indi('layout')

@def('pageTitle')Gestion des Contrats — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Page Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-2">Espace <span class="text-amber-500">Juridique.</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">Contrats · Engagements · Signatures</p>
        </div>
        
        <a href="/contrats/nouveau" class="px-8 py-4 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/10">
            Formaliser un Engagé ↗
        </a>
    </header>

    <!-- Contracts Table -->
    <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-slate-900 tracking-tight">Liste des contrats</h2>
            <div class="relative w-full max-w-xs">
                <input type="text" id="searchInput" onkeyup="filterTable('contratsTable')" placeholder="Rechercher..." class="w-full bg-slate-50 border border-slate-100 rounded-full px-6 py-3 text-sm font-bold focus:ring-2 focus:ring-brand-500 outline-none transition-all pl-12">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="contratsTable">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Référence / Objet</th>
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">État de Signature</th>
                        <th class="pb-8 text-right text-[11px] font-black uppercase tracking-widest text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-900">
                    @baat($contrats as $ctr)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="window.location='/contrats/edit/{{ $ctr->id }}'">
                        <td class="py-8">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $ctr->numero }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Contrat de Prestation de Service</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-8">
                            <div class="flex items-center gap-3">
                                @ndax($ctr->statut === 'signe')
                                    <span class="px-5 py-2 rounded-full bg-emerald-100 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Signé Digitale</span>
                                @jeexndax
                                @ndax($ctr->statut === 'brouillon')
                                    <span class="px-5 py-2 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest">Brouillon</span>
                                @jeexndax
                                @ndax($ctr->statut === 'valide' || $ctr->statut === 'envoye')
                                    <span class="px-5 py-2 rounded-full bg-brand-100 text-brand-600 text-[9px] font-black uppercase tracking-widest">En attente</span>
                                @jeexndax
                                
                                @ndax($ctr->is_read)
                                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-500 text-[8px] font-black uppercase tracking-widest border border-amber-100">👁 Consulté</span>
                                @jeexndax
                            </div>
                        </td>
                        <td class="py-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/contrats/print/{{ $ctr->id }}" target="_blank" title="Imprimer" class="p-3 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                                <a href="/view/contrat/{{ $ctr->token_public }}" target="_blank" title="Vue client" class="p-3 rounded-xl bg-slate-100 text-slate-400 hover:text-amber-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="/contrats/edit/{{ $ctr->id }}" title="Modifier" class="p-3 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-900 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @jeexbaat
                </tbody>
            </table>
        </div>

        @ndax(empty($contrats))
        <div class="py-32 text-center flex flex-col items-center gap-6">
            <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <p class="text-sm font-black text-slate-300 uppercase tracking-widest italic">Aucun document juridique trouvé.</p>
        </div>
        @jeexndax
    </div>
</div>
@jeexdef
