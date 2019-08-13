{var $site_url = 'https://krafti.ru/'}
<!DOCTYPE HTML>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>krafti.ru</title>
    <style type="text/css">
        body {
            background: #f7f7f7;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, serif;
            font-size: 14px;
            color: #293034;
        }
        table { border-spacing: 0; width: 100%; }
        table td { margin: 0; }
        body > table { width: 600px; margin: auto; }
        a { color: #369; outline: none; text-decoration: none; }
        p { font-size: 16px; line-height: 22px; }
        h1 { font-size: 22px; margin: 0 0 20px 0; font-weight: normal; }
        h1.no-margin { margin: 0; }
        h2 { font-size: 16px; margin: 5px 0 20px 0; font-weight: normal; color: #999999; }
        pre { width: 540px; overflow: auto; background: #efefef; padding: 5px; border-radius: 5px; font-size: 14px;
            font-family: Verdana, monospace;
        }
        small { font-size: 14px; color: #999999; }
        .main-logo { padding: 20px 0; text-align: center; }
        .main-logo img { width: 117px; height: 48px; border: 0; }
        .content {
            height: 100px;
            vertical-align: top;
            background: #ffffff;
            border: 1px solid #e1ddcb;
            border-radius: 5px;
            padding: 30px;
        }
        {block 'style'}{/block}
        .footer td { padding: 35px 0; }
        .footer .left { width: 150px; padding-left: 30px; }
        .footer .center a { vertical-align: middle; width: 30px; height: 30px; display: inline-block; }
        .footer .center img { width: 30px; height: 30px; }
        .footer .right { text-align: right; text-transform: uppercase; }
        .footer .right a { color: #999999; font-weight: bold; padding-right: 30px; }
        .red { color: #e74c3c; }
        .green { color: #1abc9c; }
    </style>
</head>
<body>
<table class="main">
    <thead>
    <tr>
        <td class="main-logo">
            <a href="{$site_url}" target="_blank">
                <img src="{$site_url}images/logo_email.png" alt="Krafti.ru"/>
            </a>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        {block 'content-wrapper'}
            <td class="content">
                {block 'content'}{/block}
            </td>
        {/block}
    </tr>
    <tr>
        <td>
            <table class="footer">
                <tr>
                    <td class="left">Подпишись на нас</td>
                    <td class="center">
                        <a href="https://www.instagram.com/schreinerkate/" target="_blank">
                            <img src="{$site_url}images/logo_instagram.png" alt="Instagram"/>
                        </a>
                    </td>
                    <td class="right"><a href="{$site_url}" target="_blank">Krafti.ru</a></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>