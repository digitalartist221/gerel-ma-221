<?php
namespace Core;

class SetupController {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePost();
        }
        
        $this->renderView();
    }
    
    private function handlePost() {
        $app_name = $_POST['app_name'] ?? 'Madeline App';
        $app_desc = $_POST['app_description'] ?? '';
        
        // DB Config
        $db_host = $_POST['db_host'] ?? 'localhost';
        $db_name = $_POST['db_name'] ?? '';
        $db_user = $_POST['db_user'] ?? 'root';
        $db_pass = $_POST['db_pass'] ?? '';

        // Mail Config
        $mail_from = $_POST['mail_from'] ?? 'noreply@madeline.local';
        $mail_host = $_POST['mail_host'] ?? '';
        $mail_port = $_POST['mail_port'] ?? '587';
        $mail_user = $_POST['mail_user'] ?? '';
        $mail_pass = $_POST['mail_pass'] ?? '';

        $configContent = "<?php\nreturn [\n";
        $configContent .= "    'app' => [\n";
        $configContent .= "        'name' => '{$app_name}',\n";
        $configContent .= "        'description' => '{$app_desc}',\n";
        $configContent .= "    ],\n";
        $configContent .= "    'database' => [\n";
        $configContent .= "        'host' => '{$db_host}',\n";
        $configContent .= "        'name' => '{$db_name}',\n";
        $configContent .= "        'user' => '{$db_user}',\n";
        $configContent .= "        'pass' => '{$db_pass}',\n";
        $configContent .= "    ],\n";
        $configContent .= "    'mail' => [\n";
        $configContent .= "        'from_email' => '{$mail_from}',\n";
        $configContent .= "        'from_name' => '{$app_name}',\n";
        $configContent .= "        'host' => '{$mail_host}',\n";
        $configContent .= "        'port' => '{$mail_port}',\n";
        $configContent .= "        'user' => '{$mail_user}',\n";
        $configContent .= "        'pass' => '{$mail_pass}',\n";
        $configContent .= "    ],\n";
        $configContent .= "];\n";

        $configDir = __DIR__ . '/../config';
        if (!is_dir($configDir)) mkdir($configDir, 0777, true);
        
        file_put_contents($configDir . '/app.php', $configContent);
        
        header("Location: /");
        exit;
    }

    private function renderView() {
        echo <<<HTML
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madeline — Assistant Rek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] } } }
        }
    </script>
    <style>
        body { background: #050508; color: white; -webkit-font-smoothing: antialiased; scroll-behavior: smooth; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); }
        input { background: rgba(0,0,0,0.3) !important; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 12px !important; padding: 12px 16px !important; font-size: 14px !important; width: 100%; outline: none !important; transition: all 0.3s; color: white !important; }
        input:focus { border-color: #8b5cf6 !important; box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2); }
        .btn-primary { background: white; color: black; font-weight: 800; padding: 14px; border-radius: 14px; transition: all 0.3s; cursor: pointer; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(139,92,246,0.2); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-6">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-black tracking-tighter mb-4">Initialisation <span class="text-brand-500 italic font-serif">Rek</span>.</h1>
            <p class="text-gray-500 font-light">Structurez votre application en un instant.</p>
        </div>

        <form action="/setup" method="POST" class="glass p-10 rounded-[2.5rem] space-y-10">
            <!-- App Info -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-brand-500 uppercase tracking-widest px-2">Identité</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 px-2">Nom de l'Application</label>
                        <input type="text" name="app_name" placeholder="Mon Projet Madeline" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 px-2">Description</label>
                        <input type="text" name="app_description" placeholder="Une expérience web majestueuse">
                    </div>
                </div>
            </div>

            <!-- Database -->
            <div class="pt-8 border-t border-white/5 space-y-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest px-2">Base de Données (Optionnel)</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <input type="text" name="db_host" value="localhost" placeholder="Host">
                    <input type="text" name="db_name" placeholder="Nom de la DB">
                    <input type="text" name="db_user" value="root" placeholder="Utilisateur">
                    <input type="password" name="db_pass" placeholder="Mot de passe">
                </div>
            </div>

            <!-- Mail -->
            <div class="pt-8 border-t border-white/5 space-y-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest px-2">Configuration Mail</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <input type="email" name="mail_from" placeholder="Email d'expédition (from)">
                    <input type="text" name="mail_host" placeholder="SMTP Host (ex: smtp.mailtrap.io)">
                    <input type="text" name="mail_port" value="587" placeholder="Port">
                    <input type="text" name="mail_user" placeholder="Utilisateur SMTP">
                    <input type="password" name="mail_pass" placeholder="Mot de passe SMTP">
                </div>
            </div>

            <div class="pt-8 flex flex-col gap-4 text-center">
                <button type="submit" class="btn-primary w-full">Finaliser l'Installation</button>
                <a href="/" class="text-xs text-gray-500 hover:text-white transition-colors">Ignorer et continuer</a>
            </div>
        </form>
    </div>
</body>
</html>
HTML;
    }
}
