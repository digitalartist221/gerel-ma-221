@indi('layout/print')

@def('content')
<style>
    .print-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 48px; padding-bottom: 32px; border-bottom: 3px solid #050510; }
    .company-info h2 { font-size: 24px; font-weight: 800; color: #050510; margin-bottom: 6px; }
    .company-info p { font-size: 11px; color: #666; margin: 2px 0; font-weight: 500; }
    .company-logo { max-height: 64px; max-width: 160px; object-fit: contain; margin-bottom: 12px; }
    .doc-meta { text-align: right; }
    .doc-meta .big-title { font-size: 40px; font-weight: 900; color: #e5e5e5; text-transform: uppercase; letter-spacing: -2px; display: block; }
    .doc-meta .doc-num { font-size: 18px; font-weight: 800; color: #050510; }
    .doc-meta .doc-date { font-size: 11px; color: #888; margin-top: 6px; }
    .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px; }
    .party { background: #f9f9f9; border-radius: 12px; padding: 20px 24px; }
    .party .label { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em; color: #aaa; margin-bottom: 8px; }
    .party h3 { font-size: 16px; font-weight: 800; color: #050510; }
    .party p { font-size: 11px; color: #666; margin: 2px 0; }
    .contract-intro { font-size: 13px; color: #444; line-height: 1.7; margin-bottom: 32px; padding: 20px 24px; border-left: 4px solid #050510; background: #f9f9f9; border-radius: 0 12px 12px 0; }
    .section-title { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.3em; color: #050510; margin: 32px 0 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e5e5; }
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
    .items-table thead tr th { padding: 10px 12px; text-align: left; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #050510; border-bottom: 2px solid #050510; }
    .items-table thead tr th:not(:first-child) { text-align: right; }
    .items-table tbody tr td { padding: 14px 12px; font-size: 13px; color: #333; border-bottom: 1px solid #f0f0f0; }
    .items-table tbody tr td:not(:first-child) { text-align: right; font-weight: 700; }
    .financial-block { background: #f9f9f9; border-radius: 12px; padding: 20px 24px; margin-left: auto; width: 280px; }
    .financial-row { display: flex; justify-content: space-between; font-size: 12px; color: #666; padding: 6px 0; font-weight: 600; }
    .financial-total { display: flex; justify-content: space-between; align-items: baseline; padding-top: 12px; margin-top: 8px; border-top: 2px solid #050510; }
    .financial-total .label { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #050510; }
    .financial-total .amount { font-size: 26px; font-weight: 900; color: #050510; }
    .clauses-box { font-size: 12px; color: #555; line-height: 1.8; white-space: pre-line; padding: 20px 24px; background: #f9f9f9; border-radius: 12px; font-style: italic; }
    .signatures { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-top: 48px; }
    .sig-box { border: 1.5px solid #e5e5e5; border-radius: 12px; padding: 20px; min-height: 120px; position: relative; }
    .sig-box .label { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em; color: #aaa; }
    .sig-box.signed { border-color: #16a34a; background: #f0fdf4; }
    .sig-box.signed .label { color: #16a34a; }
    .sig-stamp { position: absolute; bottom: 16px; right: 16px; font-size: 9px; color: #ccc; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; }
    .doc-footer { margin-top: auto; padding-top: 32px; border-top: 1px solid #e5e5e5; text-align: center; }
    .doc-footer p { font-size: 9px; font-weight: 700; color: #ccc; text-transform: uppercase; letter-spacing: 0.4em; }
</style>

<!-- Header: Company + Contract Reference -->
<div class="print-header">
    <div class="company-info">
        @ndax($entreprise && $entreprise->logo)
            <img src="{{ $entreprise->logo }}" class="company-logo" alt="Logo">
        @jeexndax
        <h2>{{ $entreprise ? $entreprise->nom : 'Prestataire' }}</h2>
        @ndax($entreprise && $entreprise->adresse)
            <p>{{ $entreprise->adresse }}{{ $entreprise->ville ? ', ' . $entreprise->ville : '' }}</p>
        @jeexndax
        @ndax($entreprise && $entreprise->email)
            <p>{{ $entreprise->email }}</p>
        @jeexndax
        @ndax($entreprise && $entreprise->contact)
            <p>Tél: {{ $entreprise->contact }}</p>
        @jeexndax
        @ndax($entreprise && $entreprise->siret)
            <p style="font-size:9px;color:#bbb;margin-top:6px;">Réf: {{ $entreprise->siret }}</p>
        @jeexndax
    </div>
    <div class="doc-meta">
        <span class="big-title">Contrat</span>
        <div class="doc-num">Réf: {{ $contrat->numero }}</div>
        <div class="doc-date">Date: {{ $contrat->date_signature }}</div>
    </div>
</div>

<!-- Parties -->
<div class="parties">
    <div class="party">
        <div class="label">Le Prestataire</div>
        <h3>{{ $entreprise ? $entreprise->nom : '—' }}</h3>
        @ndax($entreprise && $entreprise->adresse)
            <p>{{ $entreprise->adresse }}</p>
        @jeexndax
        @ndax($entreprise && $entreprise->email)
            <p>{{ $entreprise->email }}</p>
        @jeexndax
    </div>
    <div class="party">
        <div class="label">Le Client</div>
        <h3>{{ $client ? $client->nom : '—' }}</h3>
        @ndax($client && $client->adresse)
            <p>{{ $client->adresse }}</p>
        @jeexndax
        @ndax($client && $client->email)
            <p>{{ $client->email }}</p>
        @jeexndax
    </div>
</div>

<!-- Intro juridique -->
<div class="contract-intro">
    Entre les soussignés, <strong>{{ $entreprise ? $entreprise->nom : 'le Prestataire' }}</strong> et <strong>{{ $client ? $client->nom : 'le Client' }}</strong>, il a été convenu et arrêté ce qui suit au titre du présent contrat de prestation de service référencé <strong>{{ $contrat->numero }}</strong>.
</div>

<!-- Clauses Juridiques -->
<div class="clauses-container" style="margin-bottom: 40px;">
    <?php
    $lines = [];
    if ($contrat->contenu_json) {
        $decoded = json_decode($contrat->contenu_json, true);
        if (is_array($decoded)) $lines = $decoded;
    }
    $articleIndex = 1;
    foreach ($lines as $line):
        if (empty(trim($line['titre'] ?? '')) && empty(trim($line['description'] ?? ''))) continue;
    ?>
    <div style="margin-bottom: 24px;">
        <h4 style="font-size: 14px; font-weight: 800; color: #050510; margin-bottom: 10px;">
            Article <?php echo $articleIndex++; ?> — <?php echo htmlspecialchars($line['titre'] ?? 'Clause'); ?>
        </h4>
        <div style="font-size: 12px; color: #444; line-height: 1.8; white-space: pre-line; text-align: justify;">
            <?php echo htmlspecialchars($line['description'] ?? ''); ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Clauses -->
@ndax($contrat->notes)
    <div class="section-title">Clauses Particulières</div>
    <div class="clauses-box">{{ $contrat->notes }}</div>
@jeexndax

<div class="section-title">Fait pour valoir ce que de droit</div>
<div style="font-size:12px; color: #444; margin-bottom: 32px;">
    Fait à <strong><?php echo htmlspecialchars($entreprise ? ($entreprise->ville ?: '................') : '................'); ?></strong>, le <strong><?php echo date('d/m/Y', strtotime($contrat->date_signature)); ?></strong> en deux (2) exemplaires originaux.
</div>

<!-- Signatures -->
<div class="section-title">Signatures</div>
<div class="signatures">
    <div class="sig-box">
        <div class="label">Cachet & Signature Prestataire</div>
        <div class="sig-stamp">{{ $entreprise ? $entreprise->nom : '' }}</div>
    </div>
    <div class="sig-box {{ $contrat->statut === 'signe' ? 'signed' : '' }}">
        <div class="label">Signé numériquement — Client</div>
        @ndax($contrat->statut === 'signe')
            <p style="font-size:12px;color:#16a34a;font-weight:700;margin-top:16px;">Validé électroniquement via Madeline</p>
            <p style="font-size:10px;color:#aaa;margin-top:4px;">{{ $client ? $client->nom : '' }}</p>
        @jeexndax
    </div>
</div>

<!-- Footer -->
<div class="doc-footer">
    <p>Contrat à valeur probante — Madeline Legal Engine — {{ $contrat->numero }}</p>
</div>
@jeexdef
