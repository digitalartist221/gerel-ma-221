@indi('mail/layout')

@def('subject')Bienvenue sur Madeline !@jeexdef

@def('content')
    <h1>Bonjour <span class="accent">{{ $name ?? 'Utilisateur' }}</span>,</h1>
    <p>Nous sommes ravis de vous accueillir sur votre nouvelle plateforme propulsée par <strong>Madeline Framework</strong>.</p>
    <p>Votre environnement est maintenant prêt pour une production de haute qualité. Voici quelques ressources pour commencer :</p>
    
    <a href="{{ $url ?? '#' }}" class="btn">Accéder au Tableau de Bord</a>

    <p style="margin-top: 32px;">Si vous avez des questions, n'hésitez pas à consulter notre documentation technique.</p>
    <p>À bientôt,<br>L'équipe Madeline</p>
@jeexdef
