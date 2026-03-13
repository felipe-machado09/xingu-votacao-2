<?php

namespace Database\Seeders;

use App\Models\Audience;
use App\Models\Award;
use App\Models\Category;
use App\Models\Company;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DrawTestSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar categorias e empresas existentes
        $categories = Category::where('is_active', true)->get();
        $companies = Company::all();

        if ($categories->isEmpty() || $companies->isEmpty()) {
            $this->command->error('Rode primeiro o CsvDataSeeder para criar categorias e empresas.');
            return;
        }

        // Vincular empresas a categorias (caso não estejam)
        foreach ($categories as $category) {
            if ($category->companies()->count() === 0) {
                $randomCompanies = $companies->random(min(3, $companies->count()));
                $category->companies()->syncWithoutDetaching($randomCompanies->pluck('id'));
            }
        }

        $categories->load('companies');

        $participantes = [
            ['name' => 'Ana Clara Silva', 'email' => 'anaclara@email.com', 'phone' => '93991001001', 'birth_date' => '1995-03-15'],
            ['name' => 'Carlos Eduardo Santos', 'email' => 'carlos.edu@email.com', 'phone' => '93991002002', 'birth_date' => '1988-07-22'],
            ['name' => 'Maria Fernanda Oliveira', 'email' => 'mfernanda@email.com', 'phone' => '93991003003', 'birth_date' => '1992-11-08'],
            ['name' => 'João Pedro Almeida', 'email' => 'joaopedro@email.com', 'phone' => '93991004004', 'birth_date' => '1990-01-30'],
            ['name' => 'Beatriz Costa Lima', 'email' => 'beatriz.cl@email.com', 'phone' => '93991005005', 'birth_date' => '1997-06-12'],
            ['name' => 'Lucas Gabriel Ferreira', 'email' => 'lucasgf@email.com', 'phone' => '93991006006', 'birth_date' => '1985-09-25'],
            ['name' => 'Juliana Ribeiro Souza', 'email' => 'juliana.rs@email.com', 'phone' => '93991007007', 'birth_date' => '1993-04-18'],
            ['name' => 'Rafael Mendes Pereira', 'email' => 'rafaelmp@email.com', 'phone' => '93991008008', 'birth_date' => '1991-12-03'],
            ['name' => 'Camila Rodrigues Barros', 'email' => 'camilarb@email.com', 'phone' => '93991009009', 'birth_date' => '1996-08-20'],
            ['name' => 'Pedro Henrique Martins', 'email' => 'pedrohm@email.com', 'phone' => '93991010010', 'birth_date' => '1989-02-14'],
            ['name' => 'Larissa Moura Dias', 'email' => 'larissamd@email.com', 'phone' => '93991011011', 'birth_date' => '1994-10-07'],
            ['name' => 'Thiago Nascimento', 'email' => 'thiagonasc@email.com', 'phone' => '93991012012', 'birth_date' => '1987-05-29'],
            ['name' => 'Fernanda Gomes Araujo', 'email' => 'fegomes@email.com', 'phone' => '93991013013', 'birth_date' => '1998-01-11'],
            ['name' => 'Gustavo Lopes Xavier', 'email' => 'gustavolx@email.com', 'phone' => '93991014014', 'birth_date' => '1986-07-16'],
            ['name' => 'Isabela Freitas Carvalho', 'email' => 'isabelafc@email.com', 'phone' => '93991015015', 'birth_date' => '1999-03-23'],
            ['name' => 'Matheus Cunha Rocha', 'email' => 'matheuscr@email.com', 'phone' => '93991016016', 'birth_date' => '1992-06-05'],
            ['name' => 'Patrícia Vieira Campos', 'email' => 'patriciavc@email.com', 'phone' => '93991017017', 'birth_date' => '1990-09-19'],
            ['name' => 'Daniel Azevedo Reis', 'email' => 'danielar@email.com', 'phone' => '93991018018', 'birth_date' => '1988-11-27'],
            ['name' => 'Amanda Teixeira Ramos', 'email' => 'amandatr@email.com', 'phone' => '93991019019', 'birth_date' => '1995-04-02'],
            ['name' => 'Roberto Figueiredo Lima', 'email' => 'robertofl@email.com', 'phone' => '93991020020', 'birth_date' => '1984-08-14'],
        ];

        $audiences = [];
        foreach ($participantes as $p) {
            $audiences[] = Audience::firstOrCreate(
                ['email' => $p['email']],
                $p
            );
        }

        $this->command->info('✅ ' . count($audiences) . ' participantes criados');

        // Criar votos: cada participante vota em N categorias aleatórias
        $votesCreated = 0;
        foreach ($audiences as $index => $audience) {
            // Primeiros 5 votam em muitas categorias (elegíveis a mais prêmios)
            // O resto vota em menos
            if ($index < 5) {
                $numVotes = min($categories->count(), rand(15, $categories->count()));
            } elseif ($index < 12) {
                $numVotes = min($categories->count(), rand(5, 15));
            } else {
                $numVotes = min($categories->count(), rand(1, 5));
            }

            $selectedCategories = $categories->random($numVotes);

            foreach ($selectedCategories as $category) {
                $company = $category->companies->isNotEmpty()
                    ? $category->companies->random()
                    : $companies->random();

                $exists = Vote::where('audience_id', $audience->id)
                    ->where('category_id', $category->id)
                    ->exists();

                if (!$exists) {
                    Vote::create([
                        'audience_id' => $audience->id,
                        'category_id' => $category->id,
                        'company_id' => $company->id,
                        'ip_hash' => hash('sha256', '127.0.0.' . $index),
                        'user_agent' => 'Seeder/1.0',
                    ]);
                    $votesCreated++;
                }
            }
        }

        $this->command->info("✅ {$votesCreated} votos criados");

        // Criar prêmios para sorteio (se não existirem)
        if (Award::count() === 0) {
            $this->call(TieredAwardsSeeder::class);
            $this->command->info('✅ Prêmios criados');
        } else {
            $this->command->info('ℹ️  Prêmios já existem, pulando...');
        }

        // Resumo
        $this->command->newLine();
        $this->command->info('=== RESUMO ===');
        $this->command->info('Participantes: ' . Audience::count());
        $this->command->info('Votos totais: ' . Vote::count());
        $this->command->info('Prêmios ativos: ' . Award::where('is_active', true)->count());

        $tiers = [1 => 5, 2 => 15, 3 => 999];
        foreach ($tiers as $tier => $minVotes) {
            $eligible = \DB::table('votes')
                ->select('audience_id')
                ->groupBy('audience_id')
                ->havingRaw('COUNT(DISTINCT category_id) >= ?', [$minVotes])
                ->count();
            $this->command->info("Elegíveis Nível {$tier} (≥{$minVotes} votos): {$eligible}");
        }
    }
}
