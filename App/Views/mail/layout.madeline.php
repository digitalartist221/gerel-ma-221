<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@biir('subject')</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f7; color: #51545E; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f4f7; padding-bottom: 40px; padding-top: 40px; }
        .main { background-color: #ffffff; border-radius: 24px; width: 100%; max-width: 600px; margin: 0 auto; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.05); }
        .header { padding: 40px; text-align: center; background: #050508; }
        .logo { font-size: 24px; font-weight: 800; italic: true; color: #ffffff; letter-spacing: -1px; }
        .content { padding: 40px; line-height: 1.6; }
        .footer { padding: 40px; text-align: center; font-size: 12px; color: #6B7280; }
        .btn { display: inline-block; padding: 12px 32px; background-color: #050508; color: #ffffff !important; text-decoration: none; border-radius: 12px; font-weight: bold; margin-top: 24px; }
        h1 { color: #111827; font-size: 24px; font-weight: 800; margin-top: 0; }
        p { margin-top: 0; margin-bottom: 24px; }
        .accent { color: #8b5cf6; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <div class="logo">Madeline.</div>
            </div>
            <div class="content">
                @biir('content')
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ \Core\Config::get('app.name', 'Madeline') }}. Tous droits réservés.<br>
                Propulsé par Madeline Framework
            </div>
        </div>
    </div>
</body>
</html>
