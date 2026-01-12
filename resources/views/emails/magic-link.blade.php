<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Seu Link de Acesso</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h1 style="color: #2563eb; margin-bottom: 20px;">Seu Link de Acesso</h1>
        
        <p>Clique no botão abaixo para acessar sua conta:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #2563eb; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Acessar Agora
            </a>
        </div>
        
        <p style="color: #666; font-size: 14px;">
            Este link expira em 30 minutos. Se você não solicitou este link, pode ignorar este e-mail com segurança.
        </p>
        
        <p style="color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px;">
            Se o botão não funcionar, copie e cole este link no seu navegador:<br>
            <a href="{{ $url }}" style="color: #2563eb; word-break: break-all;">{{ $url }}</a>
        </p>
    </div>
</body>
</html>
