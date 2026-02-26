<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Melhores do Ano 2025')</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #0f0f0f; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">
    <!-- Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f0f0f;">
        <tr>
            <td align="center" style="padding: 30px 15px;">
                <!-- Container -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">

                    {{-- HEADER com Logo --}}
                    <tr>
                        <td align="center" style="padding: 40px 30px 20px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); border-radius: 16px 16px 0 0;">
                            <img src="{{ asset('img/Logomarca Melhores do Ano Branca.webp') }}"
                                 alt="Melhores do Ano 2025"
                                 width="220"
                                 style="display: block; max-width: 220px; height: auto;">
                            <div style="margin-top: 16px; width: 60px; height: 3px; background: linear-gradient(90deg, #c9a84c, #f1d98a, #c9a84c); border-radius: 2px;"></div>
                        </td>
                    </tr>

                    {{-- CONTEÚDO --}}
                    <tr>
                        <td style="background-color: #1c1c2e; padding: 40px 36px;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #1a1a2e 100%); padding: 28px 36px; border-radius: 0 0 16px 16px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="width: 40px; height: 2px; background: linear-gradient(90deg, #c9a84c, #f1d98a, #c9a84c); border-radius: 2px; margin-bottom: 18px;"></div>
                                        <p style="margin: 0 0 6px; font-size: 13px; color: #c9a84c; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">
                                            Melhores do Ano 2025
                                        </p>
                                        <p style="margin: 0 0 4px; font-size: 12px; color: #8888a4;">
                                            Vale do Xingu
                                        </p>
                                        <p style="margin: 12px 0 0; font-size: 11px; color: #555570;">
                                            Este é um e-mail automático, por favor não responda.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
