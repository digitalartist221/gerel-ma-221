@indi('layout')

@def('pageTitle')Gestion Documentaire — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Page Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-2">Documents <span class="text-brand-500">Commerciaux.</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">Cee-mi · Ndoggal · Fay-wi</p>
        </div>
        
        <a href="/documents/nouveau" class="px-8 py-4 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-widest hover:bg-brand-600 transition-all shadow-xl shadow-brand-500/10 flex items-center gap-2">
            <span>Nouveau Document</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </header>

    <!-- Documents Table -->
    <div class="p-6 md:p-12 rounded-[2rem] md:rounded-[3.5rem] bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Référence / Type</th>
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">État du Flux</th>
                        <th class="pb-8 text-[11px] font-black uppercase tracking-widest text-slate-300">Total TTC</th>
                        <th class="pb-8 text-right text-[11px] font-black uppercase tracking-widest text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-900">
                    @baat($documents as $doc)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="window.location='/documents/edit/{{ $doc->id }}'">
                        <td class="py-8">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500 group-hover:bg-brand-600 group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $doc->numero }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                        {{ $doc->type === 'devis' ? 'Cee-mi (Devis)' : ($doc->type === 'commande' ? 'Ndoggal (BC)' : 'Fay-wi (Facture)') }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-8">
                            <div class="flex items-center gap-3">
                                @ndax($doc->statut === 'paye')
                                    <span class="px-5 py-2 rounded-full bg-emerald-100 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Payé</span>
                                @jeexndax
                                @ndax($doc->statut === 'brouillon')
                                    <span class="px-5 py-2 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest">Brouillon</span>
                                @jeexndax
                                @ndax($doc->statut === 'valide')
                                    <span class="px-5 py-2 rounded-full bg-brand-100 text-brand-600 text-[9px] font-black uppercase tracking-widest">Confirmé</span>
                                @jeexndax
                                @ndax($doc->statut === 'envoye')
                                    <span class="px-5 py-2 rounded-full bg-brand-100 text-brand-600 text-[9px] font-black uppercase tracking-widest">Envoyé</span>
                                @jeexndax
                                @ndax($doc->statut === 'signe')
                                    <span class="px-5 py-2 rounded-full bg-teal-100 text-teal-600 text-[9px] font-black uppercase tracking-widest">Signé</span>
                                @jeexndax
                                
                                @ndax($doc->is_read)
                                <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse" title="Lu par le client"></div>
                                @jeexndax
                            </div>
                        </td>
                        <td class="py-8">
                            <p class="text-sm font-black text-slate-900">{{ number_format($doc->total_ttc, 0, ',', ' ') }} <span class="text-[10px] text-slate-300 font-normal">XOF</span></p>
                        </td>
                        <td class="py-8 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="/view/{{ strtolower($doc->type) }}/{{ $doc->token_public }}" target="_blank" class="p-3 rounded-xl bg-slate-100 text-slate-400 hover:text-brand-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="/documents/edit/{{ $doc->id }}" class="p-3 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-900 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @jeexbaat
                </tbody>
            </table>
        </div>

        @ndax(empty($documents))
        <div class="py-32 text-center flex flex-col items-center gap-6 animate-in slide-in-from-bottom duration-500">
            <div class="w-24 h-24 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900">Coffre-fort vide.</h3>
                <p class="text-sm text-slate-400 mt-2">Commencez par émettre votre premier document commercial.</p>
            </div>
        </div>
        @jeexndax
    </div>
</div>
@jeexdef
