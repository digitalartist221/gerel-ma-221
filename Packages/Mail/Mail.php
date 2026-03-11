<?php
namespace Packages\Mail;

use Core\Config;
use Packages\View\MadelineView;

class Mail {
    /**
     * Envoie un e-mail en utilisant un template MadelineView
     *
     * @param string $to Destinataire
     * @param string $subject Objet du mail
     * @param string $view Nom de la vue (ex: 'mail/welcome')
     * @param array $data Données à passer à la vue
     * @return bool
     */
    public static function send($to, $subject, $view, $data = []) {
        $config = Config::get('mail');
        
        // Rendu du contenu via le moteur de vues
        $htmlContent = MadelineView::render($view, $data);
        
        $fromEmail = $config['from_email'] ?? 'noreply@madeline.local';
        $fromName = $config['from_name'] ?? Config::get('app.name', 'Madeline');

        // Headers de base pour HTML
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . $fromName . ' <' . $fromEmail . '>',
            'Reply-To: ' . $fromEmail,
            'X-Mailer: MadelineFramework/' . (Config::get('app.version', '1.0'))
        ];

        // En mode local, on peut logger au lieu d'envoyer réellement si pas de SMTP configuré
        if (Config::get('env') === 'local' && empty($config['host'])) {
            error_log("Mail Simulation to $to: [$subject]");
            return true;
        }

        // Si SMTP est configuré (Simple wrapper pour l'instant, peut évoluer vers PHPMailer/Swift)
        if (!empty($config['host'])) {
            return self::sendSmtp($to, $subject, $htmlContent, $headers, $config);
        }

        // Sinon envoi via mail() natif
        return mail($to, $subject, $htmlContent, implode("\r\n", $headers));
    }

    /**
     * Placeholder pour une future implémentation SMTP plus robuste
     * Pour l'instant on utilise mail() mais on est prêt pour l'injection
     */
    private static function sendSmtp($to, $subject, $content, $headers, $config) {
        // Dans une version industrielle, on injecterait ici PHPMailer ou un client SMTP socket
        // Pour Madeline, on garde la simplicité mail() si le serveur est configuré
        // ou on invite l'utilisateur à installer un driver via Composer.
        return mail($to, $subject, $content, implode("\r\n", $headers));
    }
}
