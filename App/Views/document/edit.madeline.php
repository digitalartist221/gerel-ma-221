@indi('layout')

@def('pageTitle'){{ $document ? $document->numero : 'Édition Document' }} — Madeline@jeexdef

@def('content')
<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Sophisticated Header -->
    <header class="flex flex-col md:flex-row justify-between items-end gap-6 pb-10 border-b border-slate-100">
        <div class="space-y-2">
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2rem] text-slate-300">
                <a href="/documents" class="hover:text-slate-900 transition-colors">Documents</a>
                <span>/</span>
                <span class="text-slate-400 font-black italic">{{ $document ? $document->numero : 'Ébauche' }}</span>
            </nav>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter">
                {{ $document ? 'Révision Document.' : 'Nouveau Flux.' }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            @ndax($document)
                <div class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-100 shadow-sm">
                    <a href="/documents/send/{{ $document->id }}" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-900 bg-white shadow-sm hover:bg-slate-50 transition-all inline-flex items-center gap-2">
                         <span>Email</span>
                         <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="/documents/print/{{ $document->id }}" target="_blank" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-all">
                        PDF
                    </a>
                </div>
                <!-- Action Flow -->
                <div class="flex items-center gap-3">
                    @ndax($document->type === 'Bon de commande')
                        <a href="/documents/transform/{{ $document->id }}/livraison" class="px-6 py-3 rounded-2xl bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/10 flex items-center gap-2">
                            <span>Générer BL</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011 1v2a1 1 0 01-1 1m0-4h3m-3 4h3m4-1a3 3 0 01-3-3V7a3 3 0 013-3h3a3 3 0 013 3v5.586a1 1 0 01-.293.707l-4.086 4.086a1 1 0 01-.707.293H17z"/></svg>
                        </a>
                    @jeexndax
                    @ndax($document->type === 'Bon de livraison')
                        <a href="/documents/transform/{{ $document->id }}/facture" class="px-6 py-3 rounded-2xl bg-brand-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-brand-500/10 flex items-center gap-2">
                            <span>Facturer</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </a>
                    @jeexndax
                </div>
            @jeexndax
        </div>
    </header>

    <form action="/documents/save" method="POST" id="invoice-form" class="space-y-12">
        @csrf
        @ndax($document)
            <input type="hidden" name="id" value="{{ $document->id }}">
        @jeexndax
        <input type="hidden" name="lines_json" id="lines_json" value="">
        <input type="hidden" name="statut" id="statut_field" value="{{ $document->statut ?? 'brouillon' }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Main Content Area -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Data Entry Card -->
                <div class="p-12 rounded-[3.5rem] bg-white border border-slate-100 shadow-sm space-y-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Nature du Document</label>
                            <select name="type" class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-5 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 outline-none transition-all appearance-none cursor-pointer">
                                <option value="Fay-wi (Facture)" {{ ($document && ($document->type == 'Fay-wi (Facture)' || $document->type == 'Facture')) ? 'selected' : '' }}>Fay-wi (Facture)</option>
                                <option value="Ndoggal (BC)" {{ ($document && ($document->type == 'Ndoggal (BC)' || $document->type == 'Bon de commande')) ? 'selected' : '' }}>Ndoggal (Bon de commande)</option>
                                <option value="Cee-mi (Devis)" {{ ($document && ($document->type == 'Cee-mi (Devis)' || $document->type == 'Devis')) ? 'selected' : '' }}>Cee-mi (Devis / Proforma)</option>
                                <option value="Bon de livraison" {{ ($document && $document->type == 'Bon de livraison') ? 'selected' : '' }}>Bon de livraison</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Référence Interne</label>
                            <input type="text" name="numero" value="{{ $document->numero ?? '' }}" placeholder="ex: FC-24-001" class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-5 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 outline-none transition-all">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Partenaire Client</label>
                            <select name="client_id" required class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-5 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 outline-none transition-all appearance-none cursor-pointer">
                                <option value="">Choisir un partenaire...</option>
                                @baat($clients as $cl)
                                    <option value="{{ $cl->id }}" {{ ($document && $document->client_id == $cl->id) ? 'selected' : '' }}>{{ $cl->nom }}</option>
                                @jeexbaat
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Entité Émettrice</label>
                            <select name="entreprise_id" required class="w-full bg-slate-50 border border-slate-50 rounded-3xl px-8 py-5 text-sm font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-brand-500/5 outline-none transition-all appearance-none cursor-pointer">
                                @baat($entreprises as $ent)
                                    <option value="{{ $ent->id }}" {{ ($document && $document->entreprise_id == $ent->id) || (isset($default_entreprise_id) && $default_entreprise_id == $ent->id) ? 'selected' : '' }}>
                                        {{ $ent->nom }}
                                    </option>
                                @jeexbaat
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-6">Configuration Fiscale</label>
                            <div class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-50">
                                <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-[9px] font-black uppercase tracking-widest transition-all has-[:checked]:bg-white has-[:checked]:text-brand-600 has-[:checked]:shadow-sm">
                                    <input type="radio" name="tax_enabled" value="1" class="hidden" onchange="renderLines()" {{ ($document && $document->tax_enabled) ? 'checked' : '' }}> Régime Taxable
                                </label>
                                <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-[9px] font-black uppercase tracking-widest transition-all has-[:checked]:bg-white has-[:checked]:text-slate-400 has-[:checked]:shadow-sm">
                                    <input type="radio" name="tax_enabled" value="0" class="hidden" onchange="renderLines()" {{ (!$document || !$document->tax_enabled) ? 'checked' : '' }}> Exonération
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Line Items Editor -->
                <div class="p-10 rounded-[3rem] bg-white border border-slate-100 shadow-sm space-y-8">
                    <div class="flex items-center justify-between pb-6 border-b border-slate-50">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900">Articles & Prestations.</h3>
                        <div class="flex items-center gap-3">
                             <select id="catalog-select" class="text-[9px] font-black bg-slate-50 border border-slate-50 rounded-2xl px-4 py-2.5 focus:ring-4 focus:ring-brand-500/5 cursor-pointer outline-none">
                                <option value="">+ Catalogue</option>
                                @baat($produits as $p)
                                    <option value="{{ $p->id }}" data-nom="{{ $p->designation }}" data-prix="{{ $p->prix_unitaire }}" data-tva="{{ $p->tva }}">
                                        {{ $p->designation }} ({{ number_format($p->prix_unitaire, 0) }} XOF)
                                    </option>
                                @jeexbaat
                            </select>
                            <button type="button" onclick="addLine()" class="px-5 py-2.5 rounded-2xl bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest hover:bg-brand-600 transition-all">+ Ligne Vide</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-[9px] font-black uppercase tracking-[0.2em] text-slate-300">
                                    <th class="pb-6 pr-4">Description</th>
                                    <th class="pb-6 text-right w-20">Qté</th>
                                    <th class="pb-6 text-right w-36">P.U (XOF)</th>
                                    <th class="pb-6 text-right w-20">TVA</th>
                                    <th class="pb-6 text-right w-40">Total</th>
                                    <th class="pb-6 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="lines-body" class="divide-y divide-slate-50">
                                <!-- JS Populated -->
                            </tbody>
                        </table>
                    </div>

                    <div id="empty-state" class="hidden py-24 text-center space-y-4">
                        <div class="w-16 h-16 rounded-[2rem] bg-slate-50 flex items-center justify-center mx-auto text-slate-100">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Workspace Vide.</p>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <div class="p-8 rounded-[3rem] bg-slate-900 text-white shadow-2xl shadow-slate-900/10 sticky top-10 space-y-10">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-white/50">
                            <span class="text-[9px] font-black uppercase tracking-widest">Global HT</span>
                            <span class="font-black" id="display-ht">0 XOF</span>
                        </div>
                        <div id="tax-rows" class="space-y-4 pt-4 border-t border-white/5">
                            <!-- JS Populated -->
                        </div>
                        <div class="pt-8 border-t-2 border-white/10">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-brand-400 mb-2">Total Résolu TTC</p>
                            <p class="text-4xl font-black tracking-tighter" id="display-ttc">0 <span class="text-xs text-white/30 ml-1">XOF</span></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button type="submit" class="w-full bg-brand-600 text-white rounded-full py-6 text-[10px] font-black uppercase tracking-widest hover:bg-brand-500 transition-all shadow-xl shadow-brand-500/10 flex items-center justify-center gap-3">
                            <span>{{ $document ? 'Mettre à jour' : 'Créer le Document' }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                        
                        @ndax($document && ($document->statut === 'brouillon' || $document->statut === 'envoye'))
                            <button type="button" onclick="setStatus('valide')" class="w-full h-16 rounded-2xl bg-white/5 border border-white/10 text-white text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Valider & Sceller
                            </button>
                        @jeexndax

                        <div class="pt-4 space-y-3">
                             @ndax($document && $document->sent_at)
                                <div class="flex items-center gap-2 text-[8px] font-black uppercase tracking-[0.2em] text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Envoyé le {{ $document->sent_at }}
                                </div>
                            @jeexndax
                            @ndax($document && $document->is_read)
                                <div class="flex items-center gap-2 text-[8px] font-black uppercase tracking-[0.2em] text-brand-400 bg-brand-400/10 p-3 rounded-xl border border-brand-400/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Consulté le {{ $document->read_at }}
                                </div>
                            @jeexndax
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tax Type Modal Logic (Sénégal) -->
<div class="fixed top-8 left-1/2 -translate-x-1/2 z-[60]">
    <select name="tax_type" onchange="renderLines()" class="bg-white border border-slate-100 rounded-full px-6 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-900 shadow-2xl shadow-slate-900/10 outline-none cursor-pointer">
        <option value="TVA" {{ ($document && $document->brs_amount == 0) ? 'selected' : '' }}>Sénégal : TVA (18%)</option>
        <option value="BRS" {{ ($document && $document->brs_amount > 0) ? 'selected' : '' }}>Sénégal : BRS (5%)</option>
    </select>
</div>

<script>
// ============================================================
// Madeline Document Editor - JS Engine (Sprrrint Edition)
// ============================================================

var rawLines = {!! isset($document) && $document && $document->contenu_json ? $document->contenu_json : '[]' !!};
if (typeof rawLines === 'string') { try { rawLines = JSON.parse(rawLines); } catch(e) { rawLines = []; } }
var lines = Array.isArray(rawLines) ? rawLines : [];

var catalog = {
    @baat($produits as $p)
        "{{ $p->id }}": { id: "{{ $p->id }}", nom: "{!! addslashes($p->designation) !!}", prix: {{ $p->prix_unitaire }}, tva: {{ $p->tva }} },
    @jeexbaat
};

if (lines.length === 0) lines = [{ designation: '', qty: 1, prix: 0, tva: 18, product_id: null }];
renderLines();

// Listen to catalog selection
document.getElementById('catalog-select').addEventListener('change', function() {
    var id = this.value;
    if (!id) return;
    var p = catalog[id];
    var emptyIdx = lines.findIndex(l => !l.designation && l.prix === 0);
    var newLine = { designation: p.nom, qty: 1, prix: p.prix, tva: p.tva, product_id: id };
    if (emptyIdx >= 0) lines[emptyIdx] = newLine; else lines.push(newLine);
    renderLines();
    this.value = '';
});

function renderLines() {
    const body = document.getElementById('lines-body');
    const emptyState = document.getElementById('empty-state');
    body.innerHTML = '';
    let totalHt = 0;

    if (lines.length === 0) { emptyState.classList.remove('hidden'); updateTotals(0); document.getElementById('lines_json').value = '[]'; return; }
    emptyState.classList.add('hidden');

    lines.forEach((line, i) => {
        const qty = parseFloat(line.qty) || 0;
        const prix = parseFloat(line.prix) || 0;
        const ht = qty * prix;
        totalHt += ht;

        const tr = document.createElement('tr');
        tr.className = 'group hover:bg-slate-50/50 transition-colors cursor-default';
        tr.innerHTML = `
            <td class="py-6 pr-4">
                <input type="text" value="${escHtml(line.designation)}" onchange="updateLine(${i}, 'designation', this.value)" placeholder="Libellé de la prestation..." class="w-full bg-transparent border-0 p-0 text-sm font-black text-slate-900 focus:ring-0 placeholder-slate-200 outline-none">
            </td>
            <td class="py-6 px-2">
                <input type="number" value="${qty}" onchange="updateLine(${i}, 'qty', this.value)" class="w-full bg-transparent border-0 p-0 text-right text-sm font-black text-slate-900 focus:ring-0 outline-none">
            </td>
            <td class="py-6 px-2">
                <input type="number" value="${prix}" onchange="updateLine(${i}, 'prix', this.value)" class="w-full bg-transparent border-0 p-0 text-right text-sm font-black text-slate-900 focus:ring-0 outline-none">
            </td>
            <td class="py-6 px-2">
                <input type="number" value="${line.tva}" onchange="updateLine(${i}, 'tva', this.value)" class="w-full bg-transparent border-0 p-0 text-right text-[10px] font-black text-slate-300 focus:ring-0 outline-none">
            </td>
            <td class="py-6 pl-4 text-right text-sm font-black text-slate-900">${formatNum(ht)}</td>
            <td class="py-6 text-right">
                <button type="button" onclick="removeLine(${i})" class="w-8 h-8 rounded-full text-slate-200 hover:text-red-500 hover:bg-red-50 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </td>
        `;
        body.appendChild(tr);
    });

    updateTotals(totalHt);
    document.getElementById('lines_json').value = JSON.stringify(lines);
}

function updateTotals(ht) {
    const taxRows = document.getElementById('tax-rows');
    const taxEnabled = document.querySelector('input[name="tax_enabled"]:checked')?.value === '1';
    const taxType = document.querySelector('select[name="tax_type"]').value;
    taxRows.innerHTML = '';
    let taxVal = 0, label = '';

    if (taxEnabled) {
        if (taxType === 'TVA') { taxVal = ht * 0.18; label = 'TVA (18%)'; addTaxRow(label, taxVal, 'text-white/50'); }
        else if (taxType === 'BRS') { taxVal = ht * -0.05; label = 'BRS (-5%)'; addTaxRow(label, taxVal, 'text-orange-400'); }
    }

    const ttc = ht + (taxType === 'TVA' && taxEnabled ? taxVal : (taxType === 'BRS' && taxEnabled ? taxVal : 0));
    document.getElementById('display-ht').textContent = formatNum(ht) + ' XOF';
    document.getElementById('display-ttc').innerHTML = formatNum(ttc) + ' <span class="text-xs text-white/30 ml-1">XOF</span>';
}

function addTaxRow(label, amount, color) {
    document.getElementById('tax-rows').innerHTML += `
        <div class="flex justify-between items-center ${color}">
            <span class="text-[9px] font-black uppercase tracking-widest opacity-60">${label}</span>
            <span class="font-black text-[11px]">${formatNum(amount)} XOF</span>
        </div>
    `;
}

function addLine() { lines.push({ designation: '', qty: 1, prix: 0, tva: 18, product_id: null }); renderLines(); }
function removeLine(i) { lines.splice(i, 1); renderLines(); }
function updateLine(i, key, val) { lines[i][key] = (key === 'designation') ? val : (parseFloat(val) || 0); renderLines(); }
function setStatus(s) { document.getElementById('statut_field').value = s; document.getElementById('invoice-form').submit(); }
function formatNum(n) { return Math.round(n).toLocaleString('fr-FR'); }
function escHtml(s) { if (!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
</script>
@jeexdef
