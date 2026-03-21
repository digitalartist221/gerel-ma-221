<?php
namespace Packages\Mail;

use Core\Config;
use Packages\View\MadelineView;

class Mail {
    /**
     * Alias intuitif pour send()
     */
    public static function to($to, $subject, $body, $data = []) {
        return self::send($to, $subject, 'mail/default', ['body' => $body]);
    }

    /**
     * Envoie un e-mail en utilisant un template MadelineView
     *
     * @param string $to       Destinataire
     * @param string $subject  Objet du mail
     * @param string $view     Nom de la vue (ex: 'mail/welcome')
     * @param array  $data     Données à passer à la vue
     * @return bool
     */
    public static function send($to, $subject, $view, $data = []) {
        $config = Config::get('mail');

        // Rendu du contenu via le moteur de vues (En mode RAW pour éviter l'interruption SPA)
        $htmlContent = MadelineView::render($view, $data, true);

        $fromEmail = $config['from_email'] ?? 'noreply@madeline.local';
        $fromName  = $config['from_name']  ?? Config::get('app.name', 'Madeline');

        // En mode local sans SMTP configuré, on simule et on log
        if (Config::get('env') === 'local' && empty($config['host'])) {
            error_log("Mail Simulation to $to: [$subject]");
            return true;
        }

        // Si SMTP est configuré → envoi SMTP réel
        if (!empty($config['host'])) {
            return self::sendSmtp($to, $subject, $htmlContent, $fromEmail, $fromName, $config);
        }

        // Sinon fallback via mail() natif PHP
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . $fromName . ' <' . $fromEmail . '>',
            'Reply-To: ' . $fromEmail,
        ]);
        return mail($to, $subject, $htmlContent, $headers);
    }

    /**
     * Envoi SMTP réel via socket (sans dépendance externe)
     * Compatible avec Mailtrap, Gmail (TLS), OVH, etc.
     */
    private static function sendSmtp($to, $subject, $htmlContent, $fromEmail, $fromName, $config) {
        $host = $config['host'];
        $port = (int)($config['port'] ?? 587);
        $user = $config['user'] ?? '';
        $pass = $config['pass'] ?? '';

        try {
            // Connexion au serveur SMTP
            $socket = @fsockopen($host, $port, $errno, $errstr, 10);
            if (!$socket) {
                error_log("[Mail SMTP] Connexion échouée: $errstr ($errno)");
                return false;
            }

            $read = fgets($socket, 512);
            if (substr($read, 0, 3) !== '220') {
                error_log("[Mail SMTP] Serveur non prêt: $read");
                fclose($socket);
                return false;
            }

            $domain = explode('@', $fromEmail)[1] ?? 'localhost';

            $cmds = [
                "EHLO $domain\r\n"                                    => '250',
                "AUTH LOGIN\r\n"                                       => '334',
                base64_encode($user) . "\r\n"                          => '334',
                base64_encode($pass) . "\r\n"                          => '235',
                "MAIL FROM:<$fromEmail>\r\n"                           => '250',
                "RCPT TO:<$to>\r\n"                                    => '250',
                "DATA\r\n"                                             => '354',
            ];

            foreach ($cmds as $cmd => $expectedCode) {
                fwrite($socket, $cmd);
                $response = fgets($socket, 512);
                if (substr($response, 0, 3) !== $expectedCode) {
                    error_log("[Mail SMTP] Commande ($cmd) rejetée: $response");
                    fclose($socket);
                    return false;
                }
            }

            // Envoi du corps du message
            $message  = "From: $fromName <$fromEmail>\r\n";
            $message .= "To: $to\r\n";
            $message .= "Subject: $subject\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= "Content-Type: text/html; charset=utf-8\r\n";
            $message .= "X-Mailer: MadelineFramework\r\n";
            $message .= "\r\n";
            $message .= $htmlContent;
            $message .= "\r\n.\r\n";

            fwrite($socket, $message);
            $response = fgets($socket, 512);

            fwrite($socket, "QUIT\r\n");
            fclose($socket);

            if (substr($response, 0, 3) === '250') {
                return true;
            }

            error_log("[Mail SMTP] Erreur envoi: $response");
            return false;

        } catch (\Throwable $e) {
            error_log("[Mail SMTP] Exception: " . $e->getMessage());
            return false;
        }
    }
}
