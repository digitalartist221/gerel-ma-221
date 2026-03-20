<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding-bottom: 60px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; border-radius: 40px; overflow: hidden; margin-top: 40px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
        .header { background-color: #050510; padding: 60px 40px; text-align: center; }
        .content { padding: 60px 50px; color: #1e293b; line-height: 1.8; font-size: 15px; }
        .footer { text-align: center; padding: 40px; font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; }
        h1 { font-size: 32px; font-weight: 900; letter-spacing: -1px; margin: 0; color: #ffffff; }
        .btn { display: inline-block; padding: 18px 36px; background-color: #050510; color: #ffffff !important; text-decoration: none; border-radius: 20px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <h1>Gerel Ma Business</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    @jeexcontent
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} {{ \Core\Config::get('app.name', 'Madeline') }} Suite Commerciale<br>
                    Solution de Gestion 360&deg; &middot; S&eacute;n&eacute;gal
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
