<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression — Madeline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f5f5f5;
            color: #050510;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .no-print {
            background: #050510;
            color: white;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .no-print button {
            background: white;
            color: #050510;
            border: none;
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
        }
        .no-print button:hover { background: #e5e5e5; }
        .no-print span { font-size: 12px; font-weight: 600; opacity: 0.7; }
        .page {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 60px;
            min-height: 1200px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 80px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .page { margin: 0; box-shadow: none; border-radius: 0; padding: 40px; min-height: 100vh; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <span>Aperçu avant impression</span>
        <button onclick="window.print()">Imprimer / Télécharger PDF</button>
        <button onclick="window.close()" class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            Fermer
        </button>
    </div>
    <div class="page">
        @biir('content')
    </div>
</body>
</html>
