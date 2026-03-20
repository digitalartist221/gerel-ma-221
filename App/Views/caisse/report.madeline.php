<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilan Fiscal — Gerel Ma Business</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { brand: { 50:'#fdf4ff', 100:'#fae8ff', 400:'#c084fc', 500:'#a855f7', 600:'#9333ea', 900:'#3b0764' } }
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        body { background: #f8fafc; }

        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 18mm 20mm;
            margin: 10mm auto;
            background: #fff;
            box-shadow: 0 8px 40px rgba(0,0,0,0.07);
            border-radius: 4mm;
        }

        @media print {
            * { print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; }
            body { background: #fff; margin: 0; }
            .no-print { display: none !important; }
            .a4-page {
                margin: 0; padding: 15mm 18mm;
                box-shadow: none; border-radius: 0;
                width: 100%; min-height: 100vh;
            }
            @page { margin: 0; size: A4 portrait; }
        }
    </style>
</head>
<body class="py-10 font-sans">

<!-- Action Bar (no-print) -->
<div class="w-[210mm] mx-auto flex items-center justify-between mb-5 px-2 no-print">
    <a href="/caisse" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour Trésorerie
    </a>
    <button onclick="window.print()"
            class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-brand-600 transition-all shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Imprimer / PDF
    </button>
</div>

<!-- A4 Document -->
<div class="a4-page">

    <!-- Document Header -->
    <header class="flex justify-between items-start pb-8 mb-12 border-b-2 border-slate-900">
        <div>
            <h1 class="text-4xl font-black tracking-tighter text-slate-900 uppercase">Bilan Fiscal</h1>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mt-1">Rapport de trésorerie consolidé</p>
        </div>
        <div class="text-right">
            <div class="flex items-center gap-2 justify-end mb-1">
                <div class="w-2.5 h-2.5 rounded-full bg-brand-500"></div>
                <span class="text-lg font-black tracking-tight text-slate-900">Gerel Ma<span class="text-brand-500">.</span></span>
            </div>
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Édité le <?php echo date('d/m/Y'); ?></p>
        </div>
    </header>

    <!-- Hero KPIs -->
    <section class="grid grid-cols-3 gap-6 mb-12">
        <!-- Net Revenue -->
        <div class="col-span-2 p-8 rounded-[1.5rem] bg-slate-900 text-white">
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-brand-400 mb-4">Performance Nette (CA HT)</p>
            <h2 class="text-5xl font-black tracking-tighter">
                {{ number_format($stats['ventes_nettes'], 0, ',', ' ') }}
                <span class="text-sm text-white/30 tracking-widest ml-1">XOF</span>
            </h2>
            <div class="flex gap-10 mt-8 pt-8 border-t border-white/10">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">Charges Totales</p>
                    <p class="text-xl font-black text-red-400 mt-1">{{ number_format($stats['charges_totales'], 0, ',', ' ') }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">EBITDA Estimé</p>
                    <p class="text-xl font-black text-emerald-400 mt-1">{{ number_format($stats['ventes_nettes'] - $stats['charges_totales'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>

        <!-- Tax Column -->
        <div class="flex flex-col gap-4">
            <div class="flex-1 p-6 rounded-[1.5rem] bg-amber-50 border border-amber-100">
                <p class="text-[9px] font-black uppercase tracking-widest text-amber-600 mb-2">TVA Collectée (18%)</p>
                <p class="text-[9px] text-amber-400 mb-3">À reverser à la DGID</p>
                <p class="text-2xl font-black text-amber-700">{{ number_format($stats['tva_collectee'], 0, ',', ' ') }} <span class="text-xs">XOF</span></p>
            </div>
            <div class="flex-1 p-6 rounded-[1.5rem] bg-rose-50 border border-rose-100">
                <p class="text-[9px] font-black uppercase tracking-widest text-rose-600 mb-2">BRS/VRS Retenue (5%)</p>
                <p class="text-[9px] text-rose-400 mb-3">Withholding à la source</p>
                <p class="text-2xl font-black text-rose-700">{{ number_format($stats['brs_collecte'], 0, ',', ' ') }} <span class="text-xs">XOF</span></p>
            </div>
        </div>
    </section>

    <!-- Profit Net -->
    <section class="mb-12">
        <?php $profit = $stats['ventes_nettes'] - $stats['charges_totales']; ?>
        <div class="p-8 rounded-[1.5rem] border-2 {{ $profit >= 0 ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }} flex justify-between items-center">
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest {{ $profit >= 0 ? 'text-emerald-600' : 'text-red-600' }} mb-1">Résultat Net Estimé</p>
                <p class="text-[9px] font-bold {{ $profit >= 0 ? 'text-emerald-400' : 'text-red-400' }}">Avant impôt sur les sociétés (IS)</p>
            </div>
            <p class="text-4xl font-black {{ $profit >= 0 ? 'text-emerald-600' : 'text-red-600' }} tracking-tighter">
                {{ number_format($profit, 0, ',', ' ') }} <span class="text-sm">XOF</span>
            </p>
        </div>
    </section>

    <!-- Category Breakdown -->
    @ndax(!empty($stats['categories']))
    <section>
        <p class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 mb-6 border-b border-slate-100 pb-3">Répartition des Charges par Catégorie</p>
        <div class="space-y-4">
            @baat($stats['categories'] as $cat => $val)
            <div class="flex items-center gap-4">
                <span class="w-40 text-[10px] font-black uppercase tracking-wider text-slate-500 shrink-0">{{ $cat }}</span>
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: {{ $stats['charges_totales'] > 0 ? ($val / $stats['charges_totales'] * 100) : 0 }}%"></div>
                </div>
                <span class="text-[10px] font-black text-slate-900 w-32 text-right shrink-0">{{ number_format($val, 0, ',', ' ') }} XOF</span>
            </div>
            @jeexbaat
        </div>
    </section>
    @jeexndax

    <!-- Footer -->
    <footer class="mt-16 pt-8 border-t border-slate-100 text-center">
        <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-300">Ce document est généré automatiquement par Gerel Ma ERP — Il ne constitue pas une attestation fiscale légale définitive.</p>
    </footer>
</div>

</body>
</html>
