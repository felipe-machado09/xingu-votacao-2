<?php

namespace App\Console\Commands;

use App\Mail\CompanyCodeMail;
use App\Mail\MagicLinkMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAllEmails extends Command
{
    protected $signature = 'mail:test-all {email=test@example.com : O email destino (padrão: test@example.com)}';

    protected $description = 'Dispara todos os templates de email para visualização no Mailpit';

    public function handle(): int
    {
        $email = $this->argument('email');

        $this->info("📬 Disparando todos os emails para: {$email}");
        $this->newLine();

        // 1) CompanyCodeMail
        try {
            $this->components->task('CompanyCodeMail (Código de Acesso)', function () use ($email) {
                Mail::to($email)->send(new CompanyCodeMail(
                    code: '847291',
                    companyName: 'Empresa Exemplo LTDA',
                ));
            });
        } catch (\Exception $e) {
            $this->error("   ❌ Erro: {$e->getMessage()}");
        }

        // 2) MagicLinkMail
        try {
            $this->components->task('MagicLinkMail (Link de Acesso)', function () use ($email) {
                Mail::to($email)->send(new MagicLinkMail(
                    url: url('/magic-login/abc123-fake-token-xyz789'),
                ));
            });
        } catch (\Exception $e) {
            $this->error("   ❌ Erro: {$e->getMessage()}");
        }

        $this->newLine();
        $this->info('✅ Todos os emails foram disparados!');
        $this->info('🔗 Abra o Mailpit para visualizar: https://xingu-votos-2.ddev.site:8026');

        return Command::SUCCESS;
    }
}
