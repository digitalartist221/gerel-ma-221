@indi('layout')

@def('pageTitle'){{ $contrat ? $contrat->numero : 'Nouveau Contrat' }} — Juridique Madeline@jeexdef

@def('extra_head')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { border-bottom-left-radius: 2rem !important; border-bottom-right-radius: 2rem !important; border: none !important; font-family: inherit; }
    .ql-toolbar { border-top-left-radius: 2rem; border-top-right-radius: 2rem; border: none !important; background: #f9fafb; padding: 15px !important; }
    .ql-editor { min-height: 200px; font-size: 14px; line-height: 1.6; color: #374151; }
    
    #signature-pad { border: 2px dashed #e5e7eb; border-radius: 2rem; cursor: crosshair; background: #fff; width: 100%; height: 200px; touch-action: none; }
    .signature-container { position: relative; }
    .signature-actions { position: absolute; bottom: 1rem; right: 1rem; display: flex; gap: 0.5rem; }
</style>
@jeexdef

@def('content')
<div class="py-8 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-gray-100">
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-sm">
                <a href="/contrats" class="text-gray-400 hover:text-black transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    Juridique
                </a>
                <span class="text-gray-200">/</span>
                <span class="font-bold text-[#050510]">{{ $contrat ? $contrat->numero : 'Nouveau Contrat' }}</span>
            </div>
            <h1 class="text-5xl font-black text-[#050510] tracking-tight">
                {{ $contrat ? $contrat->numero : 'Contrat de Prestation' }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @ndax($contrat)
                <a href="/contrats/send/{{ $contrat->id }}" class="px-5 py-2.5 rounded-2xl bg-white border border-gray-100 text-[10px] font-black uppercase tracking-widest text-[#050510] shadow-sm hover:shadow-md transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> 
                    Envoyer
                </a>
                <a href="/contrats/print/{{ $contrat->id }}" target="_blank" class="px-5 py-2.5 rounded-2xl bg-[#050510] text-white text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Télécharger
                </a>
            @jeexndax

            <!-- Status Pills -->
            <div class="flex items-center bg-gray-50 p-1.5 rounded-2xl border border-gray-100">
                <div class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ ($contrat->statut ?? 'brouillon') === 'brouillon' ? 'bg-white text-brand-500 shadow-sm' : 'text-gray-300' }}">Brouillon</div>
                <div class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ ($contrat->statut ?? '') === 'valide' ? 'bg-white text-brand-500 shadow-sm' : 'text-gray-300' }}">Confirmé</div>
                <div class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ ($contrat->statut ?? '') === 'envoye' ? 'bg-white text-brand-500 shadow-sm' : 'text-gray-300' }}">Envoyé</div>
                <div class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ ($contrat->statut ?? '') === 'signe' ? 'bg-white text-green-500 shadow-sm' : 'text-gray-300' }} inline-flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    Signé
                </div>
            </div>
        </div>
    </div>

    <form action="/contrats/save" method="POST" id="contrat-form" class="space-y-8">
        @csrf
        @ndax($contrat)
            <input type="hidden" name="id" value="{{ $contrat->id }}">
        @jeexndax
        <input type="hidden" name="lines_json" id="lines_json" value="">
        <input type="hidden" name="statut" id="statut_field" value="{{ $contrat->statut ?? 'brouillon' }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- LEFT: Contract Editor -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Info Card -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm space-y-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-brand-500 mb-1">Générateur Juridique</p>
                            <p class="text-xs text-gray-400 font-bold">Engagement légal de prestation de service</p>
                        </div>
                        <svg class="w-8 h-8 text-brand-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Référence du contrat</label>
                            <input type="text" name="numero" value="{{ $contrat->numero ?? '' }}" placeholder="ex: CTR-2024-001" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Entité prestataire</label>
                            <select name="entreprise_id" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-brand-500 appearance-none">
                                <option value="">Sélectionner une entité...</option>
                                @baat($entreprises as $ent)
                                    <option value="{{ $ent->id }}" {{ ($contrat && $contrat->entreprise_id == $ent->id) ? 'selected' : '' }}>{{ $ent->nom }}</option>
                                @jeexbaat
                            </select>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Client / Partenaire</label>
                            <select name="client_id" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-brand-500 appearance-none">
                                <option value="">Sélectionner un client...</option>
                                @baat($clients as $cl)
                                    <option value="{{ $cl->id }}" {{ ($contrat && $contrat->client_id == $cl->id) ? 'selected' : '' }}>{{ $cl->nom }} {{ $cl->email ? '— '.$cl->email : '' }}</option>
                                @jeexbaat
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Articles Juridiques -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-[#050510]">Objet des Prestations</h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Services faisant l'objet de l'engagement juridique</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @ndax(count($articles) > 0)
                                <select id="article-select" class="text-[10px] font-black bg-gray-50 border border-gray-100 rounded-2xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 cursor-pointer">
                                    <option value="">+ Insérer une clause type</option>
                                    @baat($articles as $art)
                                        <option value="{{ $art->id }}"
                                            data-nom="{{ $art->nom }}"
                                            data-desc="{{ $art->description }}">
                                            {{ $art->numero }} — {{ $art->nom }}
                                        </option>
                                    @jeexbaat
                                </select>
                            @jeexndax
                            <button type="button" onclick="addLine()" class="px-4 py-2.5 rounded-2xl bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-wide hover:bg-amber-100 transition-all">+ Article libre</button>
                        </div>
                    </div>

                    <!-- Table of Clauses -->
                    <div class="overflow-x-auto mt-4">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-50">
                                    <th class="pb-4 text-left text-[9px] font-black uppercase tracking-widest text-gray-400 w-1/3">Titre de la clause</th>
                                    <th class="pb-4 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Contenu / Description juridique</th>
                                    <th class="pb-4 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="lines-body" class="divide-y divide-gray-50">
                            </tbody>
                        </table>
                    </div>
                    <div id="empty-state" class="hidden py-16 text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 opacity-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-300">Aucune disposition juridique</p>
                        <p class="text-xs text-gray-400 mt-1">Insérez des clauses types ou ajoutez des clauses libres.</p>
                    </div>
                </div>

                <!-- Notes / Clauses Particulières (HTML Editor) -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-10 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Clauses & Notes Particulières</label>
                        <span class="text-[9px] font-bold text-brand-500 uppercase tracking-widest italic">Éditeur HTML Activé</span>
                    </div>
                    <div id="editor-container"></div>
                    <input type="hidden" name="notes" id="notes_field" value="{{ $contrat->notes ?? '' }}">
                </div>

                <!-- Provider Signature -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-[#050510]">Signature du Prestataire</h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Votre signature officielle (Gerel Ma)</p>
                        </div>
                        <button type="button" onclick="clearSignature()" class="px-3 py-1.5 rounded-xl bg-gray-50 text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-gray-100 transition-all">Effacer</button>
                    </div>
                    <div class="signature-container">
                        <canvas id="signature-pad"></canvas>
                        <input type="hidden" name="signature_base64" id="signature_field" value="{{ $contrat->signature_hash ?? '' }}">
                    </div>
                </div>

                <!-- Client Signature (ReadOnly) -->
                <div class="bg-slate-50 rounded-[2.5rem] border border-gray-100 p-10 shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Signature du Client</h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Statut de la partie contractante</p>
                        </div>
                    </div>
                    @ndax($contrat && $contrat->statut === 'signe')
                        <div class="p-6 bg-white border border-emerald-100 rounded-3xl flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-emerald-600 mb-1">Signé par {{ $contrat->signed_by }}</p>
                                <p class="text-[9px] font-mono text-emerald-400">Le {{ $contrat->signed_client_at }}</p>
                                <p class="text-[8px] font-mono text-emerald-400/50 mt-1 truncate max-w-[200px]">Hash: {{ $contrat->signature_client_hash }}</p>
                            </div>
                            @ndax($contrat->signature_client_hash && str_contains($contrat->signature_client_hash, 'data:image'))
                                <img src="{{ $contrat->signature_client_hash }}" class="h-16 ml-auto object-contain bg-white rounded-xl border border-gray-50" />
                            @jeexndax
                        </div>
                    @jeexndax
                    @ndax(!$contrat || $contrat->statut !== 'signe')
                        <div class="py-8 text-center bg-white border border-gray-100 rounded-3xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">En attente de signature cliente</p>
                        </div>
                    @jeexndax
                </div>
            </div>

            <!-- RIGHT: Actions -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-sm sticky top-8 space-y-8">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-[#050510] mb-1">Résumé du Contrat</h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase">Nombre de dispositions</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b-2 border-[#050510]">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total des clauses</span>
                            <span class="text-3xl font-black text-[#050510]" id="display-clauses">0</span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4">
                        <button type="submit" class="w-full bg-[#050510] text-white rounded-full py-5 text-[11px] font-black uppercase tracking-widest hover:bg-gray-800 transition-all shadow-lg inline-flex items-center justify-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                             {{ $contrat ? 'Mettre à jour' : 'Créer le contrat' }}
                        </button>
                        @ndax($contrat && $contrat->statut === 'brouillon')
                            <button type="button" onclick="setStatus('valide')" class="w-full bg-white border-2 border-[#050510] text-[#050510] rounded-2xl py-4 text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all inline-flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Sécuriser & Valider
                            </button>
                        @jeexndax
                    </div>

                    @ndax($contrat && $contrat->sent_at)
                        <p class="text-[9px] font-bold text-brand-400 uppercase tracking-widest text-center inline-flex items-center gap-1 justify-center w-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> 
                            Envoyé le {{ $contrat->sent_at }}
                        </p>
                    @jeexndax
                    @ndax($contrat && $contrat->is_read)
                        <p class="text-[9px] font-bold text-green-400 uppercase tracking-widest text-center inline-flex items-center gap-1 justify-center w-full mt-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> 
                            Lu le {{ $contrat->read_at }}
                        </p>
                    @jeexndax
                    @ndax($contrat && $contrat->statut === 'signe')
                        <div class="bg-green-50 border border-green-100 rounded-2xl p-4 text-center">
                            <p class="text-[9px] font-black text-green-600 uppercase tracking-widest flex items-center gap-1 justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Signé & Scellé
                            </p>
                        </div>
                    @jeexndax
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
// ============================================================
// Madeline Contrat Editor - JS Engine (Clauses)
// ============================================================

var rawLines = {!! isset($contrat) && $contrat && $contrat->contenu_json ? $contrat->contenu_json : '[]' !!};
if (typeof rawLines === 'string') {
    try { rawLines = JSON.parse(rawLines); } catch(e) { rawLines = []; }
}
var lines = Array.isArray(rawLines) ? rawLines : [];

// Article juridique selector initialization (works for both direct load & SPA navigation)
var artSel = document.getElementById('article-select');
if (artSel) {
    artSel.addEventListener('change', function() {
        if (!this.value) return;
        var opt = this.options[this.selectedIndex];
        var nom = opt.getAttribute('data-nom');
        var desc = opt.getAttribute('data-desc');
        
        var emptyIdx = lines.findIndex(l => !l.titre && !l.description);
        var newLine = { titre: nom, description: desc, article_id: this.value };
        if (emptyIdx >= 0) {
            lines[emptyIdx] = newLine;
        } else {
            lines.push(newLine);
        }
        renderLines();
        this.value = '';
    });
}

if (lines.length === 0) lines = [{ titre: '', description: '', article_id: null }];
renderLines();

function renderLines() {
    var body = document.getElementById('lines-body');
    var emptyState = document.getElementById('empty-state');
    body.innerHTML = '';

    if (lines.length === 0) {
        emptyState.classList.remove('hidden');
        document.getElementById('display-clauses').textContent = '0';
        document.getElementById('lines_json').value = '[]';
        return;
    }
    emptyState.classList.add('hidden');

    lines.forEach((line, i) => {
        var tr = document.createElement('tr');
        tr.className = 'group hover:bg-gray-50/50 transition-colors';
        tr.innerHTML = `
            <td class="py-5 pr-4 align-top">
                <input type="text" value="${escHtml(line.titre || '')}"
                    onchange="updateLine(${i}, 'titre', this.value)"
                    placeholder="Titre de la clause..."
                    class="w-full bg-transparent border-0 p-0 text-sm font-bold focus:ring-0 text-[#050510] placeholder-gray-300">
                ${line.article_id ? `<div class="mt-2 inline-block px-2 text-[8px] font-black text-amber-500 bg-amber-50 rounded-md uppercase tracking-widest">Type #${line.article_id}</div>` : ''}
            </td>
            <td class="py-5 px-3 align-top border-l border-gray-100">
                <div id="editor-clause-${i}" class="bg-white rounded-xl border border-gray-100 clause-quill"></div>
            </td>
            <td class="py-5 text-right align-top">
                <button type="button" onclick="removeLine(${i})"
                    class="w-7 h-7 rounded-full text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
            </td>
        `;
        body.appendChild(tr);

        // Initialize Mini-Quill for this clause
        var lineQuill = new Quill('#editor-clause-' + i, {
            theme: 'snow',
            placeholder: 'Contenu de la clause...',
            modules: { toolbar: [ ['bold', 'italic'], [{'list': 'bullet'}] ] }
        });
        lineQuill.root.innerHTML = line.description || '';
        lineQuill.on('text-change', function() {
            updateLine(i, 'description', lineQuill.root.innerHTML);
        });
    });

    document.getElementById('display-clauses').textContent = lines.length;
    document.getElementById('lines_json').value = JSON.stringify(lines);
}

function addLine() {
    lines.push({ titre: '', description: '', article_id: null });
    renderLines();
}

function removeLine(i) {
    lines.splice(i, 1);
    renderLines();
}

// Initialize Quill
var quill = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'Rédigez ici les clauses spécifiques au contrat...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

// Load existing content
var existingNotes = document.getElementById('notes_field').value;
if (existingNotes) {
    quill.root.innerHTML = existingNotes;
}

// Sync content on change
quill.on('text-change', function() {
    document.getElementById('notes_field').value = quill.root.innerHTML;
});

function updateLine(i, key, val) {
    if (lines[i]) {
        lines[i][key] = val;
        // Don't re-render immediately for textareas to avoid losing focus, update JSON instead
        document.getElementById('lines_json').value = JSON.stringify(lines);
        if (key === 'titre') {
            document.getElementById('display-clauses').textContent = lines.length; // Just updating UI logic
        }
    }
}

// ============================================================
// Madeline Signature Pad Engine
// ============================================================

const canvas = document.getElementById('signature-pad');
if (canvas) {
    const ctx = canvas.getContext('2d');
    let drawing = false;

    // Resize canvas to its display width
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#050510';
        
        // Load existing signature if any
        const existing = document.getElementById('signature_field').value;
        if (existing && existing.startsWith('data:image')) {
            const img = new Image();
            img.onload = () => ctx.drawImage(img, 0, 0, canvas.offsetWidth, canvas.offsetHeight);
            img.src = existing;
        }
    }
    
    window.addEventListener('resize', resizeCanvas);
    setTimeout(resizeCanvas, 500); // Wait for animations

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(e.touches[0]); });
    canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e.touches[0]); });
    canvas.addEventListener('touchend', stopDrawing);

    function startDrawing(e) {
        drawing = true;
        ctx.beginPath();
        const rect = canvas.getBoundingClientRect();
        ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    }

    function draw(e) {
        if (!drawing) return;
        const rect = canvas.getBoundingClientRect();
        ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        ctx.stroke();
    }

    function stopDrawing() {
        drawing = false;
        document.getElementById('signature_field').value = canvas.toDataURL();
    }

    window.clearSignature = function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('signature_field').value = '';
    };
}

function setStatus(s) {
    document.getElementById('statut_field').value = s;
    document.getElementById('contrat-form').submit();
}

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@jeexdef
