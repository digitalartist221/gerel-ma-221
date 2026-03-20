@indi('layout')

@def('pageTitle')Consultation de Document — Gerel Ma Business@jeexdef

@def('content')
<div class="space-y-12 animate-in fade-in duration-700 max-w-4xl mx-auto py-10">
    <!-- Document Container -->
    <div class="p-16 rounded-[4rem] bg-white border border-slate-100 shadow-sm text-slate-900">
        <!-- Document Header -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16 pb-12 border-b border-slate-50">
            <div class="flex items-center gap-6">
                @ndax($entreprise && $entreprise->logo)
                    <img src="{{ $entreprise->logo }}" class="w-20 h-20 rounded-[2rem] object-contain bg-slate-50 p-4 border border-slate-100 shadow-sm">
                @jeexndax
                @ndax(!$entreprise || !$entreprise->logo)
                    <div class="w-20 h-20 rounded-[2.5rem] bg-slate-900 flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-slate-900/10">
                        {{ substr($doc->numero, 0, 1) }}
                    </div>
                @jeexndax
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $doc->type }} <span class="text-slate-400">#{{ $doc->numero }}</span></h1>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Émis par <span class="text-slate-900">{{ $entreprise ? $entreprise->nom : 'Gerel Ma Business' }}</span> le {{ $doc->date_emission }}</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                @ndax($doc->statut === 'paye')
                    <span class="px-6 py-2 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">Payé</span>
                @jeexndax
                @ndax($doc->statut === 'valide')
                    <span class="px-6 py-2 rounded-full bg-brand-50 text-brand-600 text-[10px] font-black uppercase tracking-widest border border-brand-100">Validé</span>
                @jeexndax
            </div>
        </header>

        <!-- Lines Content -->
        <?php $lines = json_decode($doc->contenu_json, true) ?: []; ?>
        <div class="space-y-8 mb-16">
            <div class="overflow-x-auto rounded-[2.5rem] border border-slate-50">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-6 text-[11px] font-black uppercase tracking-widest text-slate-400">Description</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black uppercase tracking-widest text-slate-400">Qté</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black uppercase tracking-widest text-slate-400">Prix Unit.</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black uppercase tracking-widest text-slate-400">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @baat($lines as $line)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-8 text-sm font-black text-slate-900">{{ $line['designation'] }}</td>
                            <td class="px-8 py-8 text-right text-sm font-bold text-slate-400">{{ $line['qty'] }}</td>
                            <td class="px-8 py-8 text-right text-sm font-bold text-slate-400">{{ number_format($line['prix'], 0, ',', ' ') }}</td>
                            <td class="px-8 py-8 text-right text-sm font-black text-slate-900">{{ number_format($line['qty'] * $line['prix'], 0, ',', ' ') }}</td>
                        </tr>
                        @jeexbaat
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals Summary -->
        <div class="flex justify-end pt-8 border-t border-slate-50">
            <div class="w-full max-w-sm space-y-6">
                <div class="flex justify-between items-center px-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Sous-total HT</span>
                    <span class="text-lg font-bold text-slate-600">{{ number_format($doc->total_ht, 0, ',', ' ') }} XOF</span>
                </div>
                @ndax($doc->tax_enabled)
                    @ndax($doc->tva_amount > 0)
                    <div class="flex justify-between items-center px-4">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">TVA (18%)</span>
                        <span class="text-sm font-bold text-slate-400">+ {{ number_format($doc->tva_amount, 0, ',', ' ') }} XOF</span>
                    </div>
                    @jeexndax
                    @ndax($doc->brs_amount > 0)
                    <div class="flex justify-between items-center px-4">
                        <span class="text-[10px] text-orange-400 font-bold uppercase tracking-widest">BRS (5%)</span>
                        <span class="text-sm font-bold text-orange-400">- {{ number_format($doc->brs_amount, 0, ',', ' ') }} XOF</span>
                    </div>
                    @jeexndax
                @jeexndax
                <div class="p-8 rounded-[2.5rem] bg-slate-900 text-white flex justify-between items-baseline shadow-xl shadow-slate-900/10">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Total Net à Payer</span>
                    <span class="text-4xl font-black">{{ number_format($doc->total_ttc, 0, ',', ' ') }} <span class="text-xs ml-1">XOF</span></span>
                </div>
            </div>
        </div>

        <!-- Action / Status Zone -->
        <div class="mt-20">
            @ndax($doc->statut === 'signe')
                <div class="p-12 rounded-[3rem] bg-emerald-50 border border-emerald-100 flex flex-col items-center gap-6 text-center shadow-sm">
                    <div class="w-20 h-20 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-2xl font-black text-slate-900">Engagement Scellé Numériquement</h4>
                        <p class="text-sm text-slate-500 font-bold uppercase tracking-widest">Signé par {{ $doc->signed_by }} le {{ $doc->signed_at }}</p>
                        <p class="text-[9px] font-mono text-emerald-400 tracking-tighter break-all uppercase border-t border-emerald-100 pt-6">ID : {{ $doc->signature_hash }}</p>
                    </div>
                </div>
            @jeexndax

            @ndax($doc->statut === 'brouillon' || $doc->statut === 'envoye')
            <form action="/view/{{ strtolower($doc->type) }}/{{ $token }}/action" method="POST" class="space-y-10">
                @csrf
                <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-100 text-slate-400 text-center italic text-sm">
                    En validant ce document, vous apposez une signature électronique simple ayant valeur d'acceptation commerciale des termes énoncés ci-dessus.
                </div>
                
                <div class="space-y-4">
                    <label class="block text-[10px] text-slate-400 font-black uppercase tracking-widest ml-6">Signature (Écrivez votre nom complet)</label>
                    <input type="text" name="signed_by" required placeholder="Ex: Jean Dupont" class="w-full bg-white border border-slate-100 rounded-3xl px-10 py-6 text-xl font-black focus:ring-4 focus:ring-slate-900/5 transition-all text-slate-900 outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button name="action" value="refuse" class="py-6 rounded-3xl border border-slate-100 text-[11px] font-black uppercase tracking-widest text-slate-300 hover:text-red-500 hover:border-red-500 transition-all">Refuser le document</button>
                    <button name="action" value="accept" class="py-6 rounded-3xl bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 flex items-center justify-center gap-3">
                        <span>Valider & Signer</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </div>
            </form>
            @jeexndax

            @ndax($doc->statut === 'envoye' || $doc->statut === 'valide' || $doc->statut === 'signe')
            <a href="/payment/init/{{ $doc->id }}" class="w-full py-8 rounded-3xl bg-slate-900 text-white flex items-center justify-center gap-4 text-xs font-black uppercase tracking-widest shadow-2xl shadow-slate-900/10 hover:bg-slate-800 transition-all border border-slate-800">
                <span>Payer par Orange Money / Wave</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @jeexndax
        </div>
    </div>
</div>
@jeexdef
