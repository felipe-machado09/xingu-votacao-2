<?php

namespace Database\Seeders;

use App\Models\Award;
use Illuminate\Database\Seeder;

class TieredAwardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar prêmios antigos (opcional - comente se não quiser apagar)
        // Award::truncate();

        // Nível 1 - Prêmios Básicos (5 votos)
        Award::create([
            'name' => 'Vale-compras R$ 100',
            'description' => 'Vale-compras de R$ 100 em lojas parceiras',
            'quantity' => 10,
            'tier' => 1,
            'min_votes' => 5,
            'is_active' => true,
        ]);

        Award::create([
            'name' => 'Kit de Produtos',
            'description' => 'Kit com produtos das empresas participantes',
            'quantity' => 15,
            'tier' => 1,
            'min_votes' => 5,
            'is_active' => true,
        ]);

        Award::create([
            'name' => 'Brinde Exclusivo',
            'description' => 'Brinde exclusivo do evento Melhores do Ano',
            'quantity' => 20,
            'tier' => 1,
            'min_votes' => 5,
            'is_active' => true,
        ]);

        // Nível 2 - Prêmios Intermediários (15 votos)
        Award::create([
            'name' => 'Vale-compras R$ 300',
            'description' => 'Vale-compras de R$ 300 em lojas parceiras',
            'quantity' => 5,
            'tier' => 2,
            'min_votes' => 15,
            'is_active' => true,
        ]);

        Award::create([
            'name' => 'Eletrodoméstico',
            'description' => 'Liquidificador, sanduicheira ou outro eletrodoméstico',
            'quantity' => 8,
            'tier' => 2,
            'min_votes' => 15,
            'is_active' => true,
        ]);

        Award::create([
            'name' => 'Cesta Premium',
            'description' => 'Cesta premium com produtos selecionados',
            'quantity' => 10,
            'tier' => 2,
            'min_votes' => 15,
            'is_active' => true,
        ]);

        // Nível 3 - Prêmio Máximo (todos os votos)
        Award::create([
            'name' => 'TV 32 Polegadas',
            'description' => 'Televisão Smart 32 polegadas',
            'quantity' => 1,
            'tier' => 3,
            'min_votes' => 999, // Será verificado como "todas as categorias"
            'is_active' => true,
        ]);

        Award::create([
            'name' => 'Vale-compras R$ 1.000',
            'description' => 'Vale-compras de R$ 1.000 em lojas parceiras',
            'quantity' => 2,
            'tier' => 3,
            'min_votes' => 999,
            'is_active' => true,
        ]);

        Award::create([
            'name' => 'Notebook',
            'description' => 'Notebook novo para estudo ou trabalho',
            'quantity' => 1,
            'tier' => 3,
            'min_votes' => 999,
            'is_active' => true,
        ]);
    }
}

