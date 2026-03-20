@indi('mail/layout')

@def('subject')Action requise : Document à consulter — {{ $entreprise_nom }}@jeexdef

@def('content')
    <div style="text-align: center; margin-bottom: 40px;">
        <div style="display: inline-block; padding: 8px 16px; background: #8b5cf610; color: #8b5cf6; border-radius: 50px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">
            Token Bridge Actif
        </div>
    </div>

    <h1>Bonjour <span class="accent">{{ $client_nom }}</span>,</h1>
    <p>L'entreprise <strong>{{ $entreprise_nom }}</strong> vient de vous envoyer un document officiel pour consultation et validation.</p>
    
    <div style="background: #f9fafb; border-radius: 20px; padding: 30px; margin: 30px 0; border: 1px solid #f3f4f6;">
        <div style="font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Document Type</div>
        <div style="font-size: 18px; font-weight: 800; color: #111827;">{{ $doc_type }} N° {{ $doc_numero }}</div>
    </div>

    <p>Vous pouvez consulter le document complet et agir (valider ou refuser) en cliquant sur le bouton ci-dessous :</p>
    
    <div style="text-align: center;">
        <a href="{{ $url_public }}" class="btn">Consulter le Document</a>
    </div>

    <p style="margin-top: 32px; font-size: 13px; color: #6b7280; font-style: italic;">
        Aucune connexion n'est requise. Ce lien est personnel et sécurisé.
    </p>

    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 40px 0;">
    
    <p>À bientôt,<br>L'équipe {{ $entreprise_nom }}</p>
@jeexdef
