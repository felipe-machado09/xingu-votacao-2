@extends('emails.layout')

@section('title', 'Seu Link de Acesso - Melhores do Ano 2025')

@section('content')
    {{-- Saudação --}}
    <p style="margin: 0 0 6px; font-size: 14px; color: #8888a4; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
        Acesso rápido
    </p>
    <h1 style="margin: 0 0 24px; font-size: 26px; color: #eeeef5; font-weight: 700; line-height: 1.3;">
        Seu Link de Acesso
    </h1>

    <p style="margin: 0 0 32px; font-size: 15px; color: #a0a0bc; line-height: 1.7;">
        Clique no botão abaixo para acessar sua conta na plataforma
        <strong style="color: #c9a84c;">Melhores do Ano 2025</strong>.
    </p>

    {{-- Botão de acesso --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 6px 0 6px;">
                <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #c9a84c 0%, #e8cc6e 50%, #c9a84c 100%); border-radius: 10px;">
                            <a href="{{ $url }}"
                               target="_blank"
                               style="display: inline-block; padding: 16px 48px; font-size: 16px; font-weight: 700; color: #1a1a2e; text-decoration: none; letter-spacing: 0.5px;">
                                ✨ Acessar Agora
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Aviso de validade --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 28px;">
        <tr>
            <td style="padding: 14px 18px; background-color: rgba(201, 168, 76, 0.08); border-left: 3px solid #c9a84c; border-radius: 0 8px 8px 0;">
                <p style="margin: 0; font-size: 13px; color: #c9a84c;">
                    ⏱ Este link expira em <strong>30 minutos</strong>.
                </p>
            </td>
        </tr>
    </table>

    {{-- Link alternativo --}}
    <p style="margin: 28px 0 8px; font-size: 13px; color: #555570;">
        Se o botão não funcionar, copie e cole este link no seu navegador:
    </p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 12px 16px; background-color: rgba(255,255,255,0.04); border-radius: 8px; word-break: break-all;">
                <a href="{{ $url }}" style="font-size: 12px; color: #c9a84c; text-decoration: underline; line-height: 1.5;">{{ $url }}</a>
            </td>
        </tr>
    </table>

    {{-- Aviso de segurança --}}
    <p style="margin: 28px 0 0; font-size: 13px; color: #555570; line-height: 1.6;">
        🔒 Se você não solicitou este link, ignore este e-mail. Sua conta permanece segura.
    </p>
@endsection
