<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'mail:test {email : O email para enviar o teste}';

    protected $description = 'Envia um email de teste para verificar configuração SMTP';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Enviando email de teste para: {$email}");

        try {
            Mail::raw('Este é um email de teste do sistema Melhores do Ano 2025. Se você recebeu este email, a configuração SMTP está funcionando corretamente!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('🎉 Teste de Email - Melhores do Ano 2025');
            });

            $this->info('✅ Email enviado com sucesso!');
            $this->info('Verifique sua caixa de entrada (e spam) em alguns minutos.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao enviar email:');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
