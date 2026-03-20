@indi('layout')

@def('pageTitle')Engagement Juridique — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700 max-w-4xl mx-auto py-10">
    <!-- Contract Container -->
    <div class="p-16 rounded-[4rem] bg-white border border-slate-100 shadow-sm text-slate-900">
        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16 pb-12 border-b border-slate-50">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-[2.5rem] bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-600/10">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Engagement <span class="text-slate-400">#{{ $contrat->numero }}</span></h1>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Espace Juridique Sécurisé</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                @ndax($contrat->statut === 'signe')
                    <span class="px-6 py-2 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100 italic">Signé & Scellé</span>
                @jeexndax
                @ndax($contrat->statut !== 'signe')
                    <span class="px-6 py-2 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100">En attente de signature</span>
                @jeexndax
            </div>
        </header>

        <!-- Prologue / Intro -->
        <div class="prose max-w-none mb-16 text-slate-600 leading-relaxed font-medium text-lg">
            {!! $contrat->notes !!}
        </div>

        <!-- Articles Registry -->
        <?php $lines = json_decode($contrat->contenu_json, true) ?: []; ?>
        @ndax(!empty($lines))
        <div class="space-y-12 mb-20">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-300 pb-4 border-b border-slate-50">Dispositions Contractuelles</h2>
            <div class="grid grid-cols-1 gap-8">
                <?php $idx = 1; foreach($lines as $line): if(empty(trim($line['titre'] ?? '')) && empty(trim($line['description'] ?? ''))) continue; ?>
                <div class="p-10 bg-slate-50/50 rounded-[3rem] border border-slate-50 group hover:border-indigo-100 transition-all">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-[9px] font-black uppercase bg-indigo-600 text-white px-3 py-1 rounded-full">Art. <?php echo $idx++; ?></span>
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest"><?php echo htmlspecialchars($line['titre'] ?? 'Clause'); ?></h4>
                    </div>
                    <div class="text-xs text-slate-500 leading-relaxed font-medium text-justify whitespace-pre-line">
                        <?php echo htmlspecialchars($line['description'] ?? ''); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        @jeexndax

        <!-- Signature Section -->
        <div class="mt-20">
            @ndax($contrat->statut !== 'signe' && $contrat->statut !== 'annule')
            <div class="space-y-10">
                <div class="bg-indigo-50/30 p-12 rounded-[3.5rem] border border-indigo-50 text-indigo-400 text-center italic text-sm">
                    En validant ce contrat, vous reconnaissez avoir pris connaissance de l'intégralité des clauses susmentionnées et y apportez votre consentement juridique permanent.
                </div>
                
                <form action="/view/contrat/{{ $contrat->token_public }}/action" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-4">
                        <label class="block text-[10px] text-slate-400 font-black uppercase tracking-widest ml-10">Signataire (Nom & Prénom)</label>
                        <input type="text" name="signed_by" required placeholder="Signature calligraphiée..." class="w-full bg-white border border-slate-100 rounded-[2.5rem] px-10 py-7 text-2xl font-black text-slate-900 focus:ring-4 focus:ring-indigo-600/5 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <button name="action" value="refuse" class="py-6 rounded-3xl border border-slate-100 text-[11px] font-black uppercase tracking-widest text-slate-300 hover:text-red-500 hover:border-red-500 transition-all">Décliner le projet</button>
                        <button name="action" value="accept" class="py-6 rounded-3xl bg-indigo-600 text-white text-[11px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-slate-900 transition-all flex items-center justify-center gap-3">
                            <span>Signer le contrat</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
            @jeexndax
            
            @ndax($contrat->statut === 'signe')
            <div class="p-16 bg-emerald-50 rounded-[4rem] border border-emerald-100 text-center flex flex-col items-center gap-8 shadow-sm">
                <div class="w-24 h-24 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="space-y-4">
                    <h4 class="text-3xl font-black text-slate-900">Engagement Scellé & Valide</h4>
                    <p class="text-sm text-emerald-600 font-black uppercase tracking-widest">Signé par {{ $contrat->signed_by }}</p>
                    <div class="mt-10 pt-10 border-t border-emerald-100 space-y-2">
                        <p class="text-[8px] font-mono text-emerald-400 uppercase tracking-tighter">Hachage Juridique : {{ $contrat->signature_hash }}</p>
                        <p class="text-[8px] font-mono text-emerald-400 uppercase tracking-tighter">Horodatage : {{ $contrat->signed_at }}</p>
                    </div>
                </div>
            </div>
            @jeexndax
        </div>
    </div>
</div>
@jeexdef
