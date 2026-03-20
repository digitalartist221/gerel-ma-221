@indi('layout/print')

@def('content')
<style>
    :root { --slate-900: #050510; --slate-400: #94a3b8; --brand-600: #8b5cf6; }
    
    .print-container { padding: 0; color: var(--slate-900); }
    
    /* Header Architecture */
    .p-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 60px; }
    .company-identity { max-width: 50%; }
    .company-logo { max-height: 80px; max-width: 200px; object-fit: contain; margin-bottom: 20px; }
    .company-name { font-size: 32px; font-weight: 900; tracking-tighter; margin-bottom: 8px; line-height: 1; }
    .company-details { font-size: 11px; color: var(--slate-400); line-height: 1.6; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    
    .doc-identity { text-align: right; }
    .doc-type-badge { background: var(--slate-900); color: white; padding: 12px 24px; border-radius: 16px; display: inline-block; font-size: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 16px; }
    .doc-number { font-size: 24px; font-weight: 900; margin-bottom: 4px; }
    .doc-date { font-size: 12px; color: var(--slate-400); font-weight: 700; }
    
    /* Partner Section */
    .partner-grid { display: grid; grid-cols: 1; margin-bottom: 60px; }
    .partner-card { background: #fafafa; border: 1px solid #f0f0f0; padding: 40px; border-radius: 32px; }
    .partner-label { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.3em; color: var(--slate-400); margin-bottom: 16px; }
    .partner-name { font-size: 22px; font-weight: 900; margin-bottom: 4px; }
    .partner-info { font-size: 13px; color: #555; line-height: 1.5; font-weight: 500; }
    
    /* Table Excellence */
    .table-container { margin-bottom: 40px; }
    .p-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .p-table th { text-align: left; padding: 16px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: var(--slate-400); border-bottom: 2px solid var(--slate-900); }
    .p-table td { padding: 20px 16px; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
    .p-table tr:last-child td { border-bottom: none; }
    .text-right { text-align: right; }
    .font-black { font-weight: 900; }
    
    /* Financial Architecture */
    .financials { display: flex; justify-content: flex-end; }
    .summary-box { width: 320px; background: var(--slate-900); color: white; padding: 40px; border-radius: 40px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 12px; font-weight: 600; opacity: 0.6; }
    .summary-total { margin-top: 24px; pt-24 border-top: 1px solid rgba(255,255,255,0.1); }
    .total-label { font-size: 10px; font-weight: 900; text-transform: uppercase; tracking-widest; margin-bottom: 4px; opacity: 0.5; }
    .total-amount { font-size: 32px; font-weight: 900; tracking-tighter; }
    
    /* Signature & Legal */
    .bottom-section { margin-top: auto; padding-top: 80px; }
    .legal-footer { text-align: center; border-top: 1px solid #eee; padding-top: 32px; }
    .studio-brand { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5em; color: #ddd; margin-top: 12px; }
</style>

<div class="print-container">
    <header class="p-header">
        <div class="company-identity">
            @ndax($entreprise && $entreprise->logo)
                <img src="{{ $entreprise->logo }}" class="company-logo">
            @jeexndax
            <h1 class="company-name">{{ $entreprise ? $entreprise->nom : 'Gerel Ma Business' }}</h1>
            <div class="company-details">
                <p>
                    {{ $entreprise->adresse ?? 'Dakar, Sénégal' }}
                    @ndax($entreprise && $entreprise->ville)
                        , {{ $entreprise->ville }}
                    @jeexndax
                </p>
                <p>
                    {{ $entreprise->contact ?? 'Contact Office' }} • {{ $entreprise->email ?? 'contact@gerelma.com' }}
                </p>
                @ndax($entreprise && $entreprise->ninea)
                    <p style="margin-top:8px; opacity:0.6;">NINEA: {{ $entreprise->ninea }} • RC: {{ $entreprise->rc }}</p>
                @jeexndax
            </div>
        </div>
        <div class="doc-identity">
            <div class="doc-type-badge">{{ $doc->type }}</div>
            <div class="doc-number">REF No. {{ $doc->numero }}</div>
            <div class="doc-date">DATE: {{ date('d/m/Y', strtotime($doc->date_emission)) }}</div>
            @ndax($doc->statut === 'paye')
                <div style="margin-top:12px; color:#10b981; font-weight:900; font-size:10px; text-transform:uppercase; letter-spacing:0.2em;">ACQUITTÉ / PAYÉ</div>
            @jeexndax
        </div>
    </header>

    <div class="partner-grid">
        <div class="partner-card">
            <p class="partner-label">À l'attention de</p>
            <h3 class="partner-name">{{ $client ? $client->nom : 'Client Divers' }}</h3>
            <div class="partner-info">
                @ndax($client)
                    <p>{{ $client->adresse ?: 'Localisation non spécifiée' }}</p>
                    <p>{{ $client->telephone ?: ($client->email ?: '--') }}</p>
                @jeexndax
                @ndax(!$client)
                    <p>Client de passage</p>
                @jeexndax
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="p-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th class="text-right">Qté</th>
                    <th class="text-right">Prix Unitaire</th>
                    <th class="text-right">Total HT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lines = json_decode($doc->contenu_json, true) ?: [];
                foreach($lines as $line):
                    $ht = ($line['qty'] ?? 0) * ($line['prix'] ?? 0);
                ?>
                <tr>
                    <td class="font-black">{{ $line['designation'] }}</td>
                    <td class="text-right">{{ $line['qty'] }}</td>
                    <td class="text-right font-black">{{ number_format($line['prix'], 0, ',', ' ') }}</td>
                    <td class="text-right font-black">{{ number_format($ht, 0, ',', ' ') }}</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="financials">
        <div class="summary-box">
            <div class="summary-row">
                <span>Total Brut HT</span>
                <span>{{ number_format($doc->total_ht, 0, ',', ' ') }} XOF</span>
            </div>
            @ndax($doc->tax_enabled)
                <div class="summary-row">
                    <span>TVA (18%)</span>
                    <span>{{ number_format($doc->tva_amount, 0, ',', ' ') }} XOF</span>
                </div>
                @ndax($doc->brs_amount > 0)
                <div class="summary-row" style="color:#fb923c; opacity:1;">
                    <span>Retenue BRS (5%)</span>
                    <span>- {{ number_format($doc->brs_amount, 0, ',', ' ') }} XOF</span>
                </div>
                @jeexndax
            @jeexndax
            
            <div class="summary-total">
                <p class="total-label">Montant Net à Payer</p>
                <p class="total-amount">{{ number_format($doc->total_ttc, 0, ',', ' ') }} <small style="font-size:12px; opacity:0.4;">XOF</small></p>
            </div>
        </div>
    </div>

    <div class="bottom-section">
        <div class="legal-footer">
            <p style="font-size: 11px; font-weight: 800; text-transform: uppercase; tracking-widest; color: #888;">Merci de votre confiance.</p>
            <div class="studio-brand">propulsé par gerel ma d digital artist studio</div>
        </div>
    </div>
</div>
@jeexdef
