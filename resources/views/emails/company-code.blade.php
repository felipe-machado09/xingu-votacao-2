<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Acesso</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ url('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Melhores do Ano" style="max-height: 80px;">
    </div>
    
    <h1 style="color: #dc2626; text-align: center; margin-bottom: 20px;">Código de Acesso</h1>
    
    <p>Olá, <strong>{{ $companyName }}</strong>!</p>
    
    <p>Seu código de acesso para entrar na plataforma é:</p>
    
    <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 20px; text-align: center; border-radius: 10px; margin: 30px 0;">
        <div style="font-size: 36px; font-weight: bold; letter-spacing: 8px;">{{ $code }}</div>
    </div>
    
    <p style="color: #666; font-size: 14px;">Este código é válido por <strong>15 minutos</strong>.</p>
    
    <p style="color: #666; font-size: 14px; margin-top: 30px;">Se você não solicitou este código, ignore este e-mail.</p>
    
    <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
    
    <p style="color: #999; font-size: 12px; text-align: center;">
        Melhores do Ano 2025 - Vale do Xingu<br>
        Este é um e-mail automático, por favor não responda.
    </p>
</body>
</html>
