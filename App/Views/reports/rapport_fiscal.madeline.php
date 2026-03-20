<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Fiscal — {{ $entreprise ? $entreprise->nom : 'Global' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
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
        body { background-color: #f1f5f9; color: #0f172a; -webkit-font-smoothing: antialiased; }
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-radius: 4mm;
        }
        @media print {
            body { background-color: white; margin: 0; }
            .a4-page { margin: 0; padding: 15mm; box-shadow: none; width: 100%; height: auto; border-radius: 0; }
            .no-print { display: none !important; }
            @page { margin: 0; size: A4; }
        }
    </style>
</head>
<body class="py-8">

    <!-- Action Bar (Not Printed) -->
    <div class="max-w-[210mm] mx-auto flex justify-between items-center mb-6 no-print px-4">
        <button onclick="window.close()" class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            Fermer
        </button>
        <button onclick="window.print()" class="px-6 py-2.5 rounded-2xl bg-indigo-600 text-white text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimer (PDF)
        </button>
    </div>

    <!-- Official Document -->
    <div class="a4-page">
        <!-- Header -->
        <header class="flex justify-between items-start mb-16 pb-8 border-b-2 border-slate-900">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-slate-900 uppercase">Rapport de Synthèse</h1>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Obligations & Flux Financiers</p>
            </div>
            <div class="text-right">
                <div class="flex items-center gap-2 justify-end mb-2">
                    <div class="w-3 h-3 bg-indigo-600 rounded-full"></div>
                    <span class="text-xl font-black tracking-tighter">Gerel Ma<span class="text-indigo-600">.</span></span>
                </div>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Édité le {{ date('d/m/Y') }}</p>
            </div>
        </header>

        <!-- Context & Enterprise -->
        <div class="flex flex-row justify-between gap-10 mb-16">
            <div class="w-1/2">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 border-b border-slate-100 pb-2">Entité Associée</p>
                @ndax($entreprise)
                    <h2 class="text-xl font-black text-slate-900 uppercase mb-2">{{ $entreprise->nom }}</h2>
                    <ul class="space-y-1 text-xs font-bold text-slate-600 uppercase">
                        <li><span class="text-slate-400 w-16 inline-block">NINEA</span> : {{ $entreprise->ninea ?? 'Non spécifié' }}</li>
                        <li><span class="text-slate-400 w-16 inline-block">RCCM</span> : {{ $entreprise->rc ?? 'Non spécifié' }}</li>
                        <li class="pt-2 text-[10px]">{{ $entreprise->adresse ?? 'Sénégal' }}</li>
                    </ul>
                @jeexndax
                @ndax(!$entreprise)
                    <h2 class="text-xl font-black text-slate-900 uppercase">Vue Globale (Toutes Entités)</h2>
                    <p class="text-xs font-bold text-slate-400 mt-2">Ce rapport agrège les données de toutes les entreprises enregistrées sur ce compte Gerel Ma.</p>
                @jeexndax
            </div>

            <div class="w-1/2">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 border-b border-slate-100 pb-2">Période Fiscale</p>
                <h3 class="text-lg font-black text-indigo-900 uppercase bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                    @ndax($period === 'this_month')Mois Courant@jeexndax
                    @ndax($period === 'last_month')Mois Précédent@jeexndax
                    @ndax($period === 'this_year')Année Courante@jeexndax
                    @ndax($period === 'all')Tout l'Historique@jeexndax
                    @ndax($period === 'custom')Personnalisée@jeexndax
                </h3>
                <p class="text-xs font-bold text-slate-500 mt-3 ml-2 uppercase tracking-widest">
                    Du : <span class="text-slate-900">{{ date('d/m/Y', strtotime($startDate)) }}</span><br>
                    Au : <span class="text-slate-900">{{ date('d/m/Y', strtotime($endDate)) }}</span>
                </p>
            </div>
        </div>

        <!-- Financial Summary -->
        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6 border-b border-slate-100 pb-2">Bilan Financier & Taxes (Sénégal)</p>
        
        <div class="space-y-4 mb-20">
            <!-- CA HT -->
            <div class="flex justify-between items-center p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <div>
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Chiffre d'Affaires Brut (HT)</h4>
                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Total facturé hors taxes</p>
                </div>
                <div class="text-2xl font-black text-slate-900">{{ number_format($ca_ht, 0, ',', ' ') }} <span class="text-xs text-slate-400">XOF</span></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- TVA -->
                <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                    <h4 class="text-[10px] font-black text-amber-900 uppercase tracking-widest mb-1">TVA Collectée (18%)</h4>
                    <p class="text-[8px] font-bold text-amber-600/60 uppercase mb-4">À reverser à la DGID</p>
                    <div class="text-xl font-black text-amber-600">{{ number_format($tva_collectee, 0, ',', ' ') }} <span class="text-[10px] text-amber-600/50">XOF</span></div>
                </div>

                <!-- BRS -->
                <div class="p-6 bg-rose-50 rounded-2xl border border-rose-100">
                    <h4 class="text-[10px] font-black text-rose-900 uppercase tracking-widest mb-1">BRS Retenue (5%)</h4>
                    <p class="text-[8px] font-bold text-rose-600/60 uppercase mb-4">Retenue à la source</p>
                    <div class="text-xl font-black text-rose-600">{{ number_format($brs_retenue, 0, ',', ' ') }} <span class="text-[10px] text-rose-600/50">XOF</span></div>
                </div>
            </div>

            <!-- Total TTC -->
            <div class="flex justify-between items-center p-8 bg-slate-900 rounded-[2rem] shadow-xl text-white mt-10">
                <div>
                    <h4 class="text-xs font-black text-brand-400 uppercase tracking-[0.3em]">Total Chiffre d'Affaires Encaisse</h4>
                    <p class="text-[9px] font-bold text-slate-500 uppercase mt-2">Base HT + TVA - BRS</p>
                </div>
                <div class="text-4xl font-black tracking-tighter">{{ number_format($total_ttc, 0, ',', ' ') }} <span class="text-xs text-slate-500 ml-1">XOF</span></div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto pt-10 border-t border-slate-100 text-center space-y-2">
            <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400">Ce document est généré de manière automatique via Gerel Ma ERP.</p>
            <p class="text-[8px] font-bold text-slate-300">Il ne constitue pas une attestation fiscale légale définitive mais une aide à la déclaration.</p>
        </footer>
    </div>

</body>
</html>
