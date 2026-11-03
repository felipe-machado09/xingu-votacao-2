@extends('emails.layout')

@section('title', 'Código de Acesso - Melhores do Ano '.\App\Models\LandingPageSection::campaignYear())

@section('content')
    {{-- Saudação --}}
    <p style="margin: 0 0 6px; font-size: 14px; color: #8888a4; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
        Bem-vindo(a)
    </p>
    <h1 style="margin: 0 0 24px; font-size: 26px; color: #eeeef5; font-weight: 700; line-height: 1.3;">
        Olá, {{ $companyName }}!
    </h1>

    <p style="margin: 0 0 28px; font-size: 15px; color: #a0a0bc; line-height: 1.7;">
        Você solicitou um código de acesso para entrar na plataforma
        <strong style="color: #c9a84c;">Melhores do Ano {{ \App\Models\LandingPageSection::campaignYear() }}</strong>. Use o código abaixo:
    </p>

    {{-- Caixa do código --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 28px 20px; background: linear-gradient(135deg, #c9a84c 0%, #e8cc6e 50%, #c9a84c 100%); border-radius: 12px;">
                <p style="margin: 0 0 8px; font-size: 11px; color: #1a1a2e; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                    Seu código de acesso
                </p>
                <p style="margin: 0; font-size: 42px; font-weight: 800; letter-spacing: 10px; color: #1a1a2e; font-family: 'Courier New', monospace;">
                    {{ $code }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Aviso de validade --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px;">
        <tr>
            <td style="padding: 14px 18px; background-color: rgba(201, 168, 76, 0.08); border-left: 3px solid #c9a84c; border-radius: 0 8px 8px 0;">
                <p style="margin: 0; font-size: 13px; color: #c9a84c;">
                    ⏱ Este código é válido por <strong>15 minutos</strong>.
                </p>
            </td>
        </tr>
    </table>

    {{-- Aviso de segurança --}}
    <p style="margin: 28px 0 0; font-size: 13px; color: #555570; line-height: 1.6;">
        🔒 Se você não solicitou este código, ignore este e-mail. Sua conta permanece segura.
    </p>
@endsection
