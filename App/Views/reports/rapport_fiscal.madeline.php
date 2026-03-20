<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Fiscal {{ $selectedYear }} — {{ $entreprise ? $entreprise->nom : 'Global' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        body { background: #f1f5f9; }

        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 18mm 20mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.07);
            border-radius: 5mm;
        }

        @media print {
            * { print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
            body { background: white; margin: 0; }
            .no-print { display: none !important; }
            .a4-page { margin: 0; padding: 15mm 18mm; box-shadow: none; border-radius: 0; width: 100%; min-height: 100vh; }
            @page { margin: 0; size: A4 portrait; }
        }
    </style>
</head>
<body class="py-8 font-sans">

<!-- ====== ACTION BAR (no-print) ====== -->
<div class="no-print max-w-[210mm] mx-auto space-y-4 mb-6 px-2">
    <!-- Config Form -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-5">
        <p class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 mb-4">⚙️ Paramètres du Rapport Fiscal</p>
        <form method="GET" action="/dashboard/fiscalite" class="flex flex-wrap items-end gap-4">
            <!-- Year -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Exercice Fiscal</label>
                <select name="year" class="bg-slate-50 border border-slate-200 rounded-xl text-sm font-black text-slate-900 px-4 py-2.5 outline-none cursor-pointer">
                    @baat($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $yr == $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
                    @jeexbaat
                </select>
            </div>

            <!-- Enterprise -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Entité</label>
                <select name="entreprise_id" class="bg-slate-50 border border-slate-200 rounded-xl text-sm font-black text-slate-900 px-4 py-2.5 outline-none cursor-pointer">
                    <option value="all" {{ $entrepriseId === 'all' ? 'selected' : '' }}>Toutes les entités</option>
                    @baat($entreprises as $ent)
                        <option value="{{ $ent->id }}" {{ $ent->id == $entrepriseId ? 'selected' : '' }}>{{ $ent->nom }}</option>
                    @jeexbaat
                </select>
            </div>

            <!-- Submit -->
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md">
                Générer
            </button>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <button onclick="window.close()" class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all shadow-sm">
            ← Fermer
        </button>
        <button onclick="window.print()" class="flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 transition-all shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimer / PDF
        </button>
    </div>
</div>

<!-- ====== A4 DOCUMENT ====== -->
<div class="a4-page">

    <!-- Header -->
    <header class="flex justify-between items-start pb-8 mb-10 border-b-2 border-slate-900">
        <div>
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-500 mb-2">Exercice Fiscal</p>
            <h1 class="text-5xl font-black tracking-tighter text-slate-900">{{ $selectedYear }}</h1>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mt-1">1 Janvier → 31 Décembre</p>
        </div>
        <div class="text-right">
            <div class="flex items-center gap-2 justify-end mb-2">
                <div class="w-3 h-3 rounded-full bg-indigo-600"></div>
                <span class="text-xl font-black tracking-tight text-slate-900">Gerel Ma<span class="text-indigo-600">.</span></span>
            </div>
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Rapport généré le <?php echo date('d/m/Y'); ?></p>
            @ndax($entreprise)
                <div class="mt-3 pt-3 border-t border-slate-100 text-right">
                    <p class="text-sm font-black text-slate-900 uppercase">{{ $entreprise->nom }}</p>
                    @ndax(!empty($entreprise->ninea))
                        <p class="text-[9px] font-bold text-slate-400 uppercase">NINEA : {{ $entreprise->ninea }}</p>
                    @jeexndax
                    @ndax(!empty($entreprise->rc))
                        <p class="text-[9px] font-bold text-slate-400 uppercase">RCCM : {{ $entreprise->rc }}</p>
                    @jeexndax
                </div>
            @jeexndax
            @ndax(!$entreprise)
                <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase">Vue Consolidée — Toutes entités</p>
            @jeexndax
        </div>
    </header>

    <!-- KPI Grid -->
    <section class="grid grid-cols-3 gap-5 mb-10">
        <!-- CA HT -->
        <div class="col-span-2 p-8 rounded-[1.5rem] bg-slate-900 text-white">
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-indigo-400 mb-3">Chiffre d'Affaires Net (HT)</p>
            <h2 class="text-5xl font-black tracking-tighter">
                {{ number_format($ca_ht, 0, ',', ' ') }}
                <span class="text-sm text-white/30 ml-1">XOF</span>
            </h2>
            <p class="text-[9px] text-white/40 uppercase tracking-widest font-bold mt-2">Revenus encaissés hors taxes</p>
        </div>

        <!-- Total TTC -->
        <div class="p-8 rounded-[1.5rem] bg-indigo-600 text-white flex flex-col justify-center">
            <p class="text-[9px] font-black uppercase tracking-[0.25em] text-indigo-200 mb-3">Total Encaissé (TTC)</p>
            <p class="text-3xl font-black tracking-tighter">{{ number_format($total_ttc, 0, ',', ' ') }}</p>
            <p class="text-[9px] text-indigo-200 uppercase tracking-widest font-bold mt-1">XOF</p>
        </div>
    </section>

    <!-- Tax Obligations -->
    <section class="grid grid-cols-2 gap-5 mb-10">
        <div class="p-7 rounded-[1.5rem] bg-amber-50 border border-amber-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-amber-700">TVA Collectée (18%)</p>
                    <p class="text-[8px] font-bold text-amber-500 uppercase mt-0.5">À reverser à la DGID</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-amber-400 flex items-center justify-center text-white text-xs font-black">18%</div>
            </div>
            <p class="text-3xl font-black text-amber-800 tracking-tighter">{{ number_format($tva_collectee, 0, ',', ' ') }} <span class="text-sm font-bold text-amber-500">XOF</span></p>
        </div>

        <div class="p-7 rounded-[1.5rem] bg-rose-50 border border-rose-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-rose-700">BRS Retenue (5%)</p>
                    <p class="text-[8px] font-bold text-rose-500 uppercase mt-0.5">Retenue à la source</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-rose-400 flex items-center justify-center text-white text-xs font-black">5%</div>
            </div>
            <p class="text-3xl font-black text-rose-800 tracking-tighter">{{ number_format($brs_retenue, 0, ',', ' ') }} <span class="text-sm font-bold text-rose-500">XOF</span></p>
        </div>
    </section>

    <!-- Monthly Evolution Table -->
    <section class="mb-10">
        <p class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 mb-5 pb-3 border-b border-slate-100">Évolution Mensuelle du CA ({{ $selectedYear }})</p>
        <div class="grid grid-cols-12 gap-2 items-end h-28">
            <?php
                $months = ['J','F','M','A','M','J','J','A','S','O','N','D'];
                $maxCA = max(array_map('floatval', $monthlyCA)) ?: 1;
                foreach ($monthlyCA as $i => $val):
                    $pct = round(($val / $maxCA) * 100);
                    $active = $pct > 0 ? 'bg-indigo-500' : 'bg-slate-100';
            ?>
            <div class="flex flex-col items-center gap-1">
                <div class="w-full rounded-t-lg <?= $active ?>" style="height: <?= max($pct, 4) ?>%"></div>
                <span class="text-[8px] font-black text-slate-400 uppercase"><?= $months[$i] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="pt-8 border-t border-slate-100 flex justify-between items-end">
        <p class="text-[7px] font-bold uppercase tracking-widest text-slate-300 max-w-80 leading-relaxed">Document généré automatiquement par Gerel Ma ERP — Données issues du Journal de Caisse. Ce document n'est pas une attestation fiscale légale définitive.</p>
        <div class="text-right">
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider">Signature Responsable</p>
            <div class="w-40 border-b border-slate-200 mt-8"></div>
        </div>
    </footer>

</div>

</body>
</html>
