<?php

namespace Database\Seeders;

use App\Models\Winner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class WinnerSeeder extends Seeder
{
    public function run(): void
    {
        $winners = [
            [
                'name' => 'Bruno',
                'category' => 'Ganhador da Fritadeira Elétrica',
                'company_name' => null,
                'image' => 'Bruno - Ganhador da Fritadeira Elétrica.png',
                'description' => 'Ganhador da Fritadeira Elétrica',
                'year' => 2024,
                'order' => 1,
            ],
            [
                'name' => 'Adriano',
                'category' => 'Armazém da Construção',
                'company_name' => 'Armazém da Construção',
                'image' => 'Adriano - Armazém da Construção.png',
                'description' => null,
                'year' => 2024,
                'order' => 2,
            ],
            [
                'name' => 'A Nova Era das Bikes',
                'category' => null,
                'company_name' => 'A Nova Era das Bikes',
                'image' => 'A Nova Era das Bikes.png',
                'description' => null,
                'year' => 2024,
                'order' => 3,
            ],
            [
                'name' => 'Auto Posto Vitória',
                'category' => null,
                'company_name' => 'Auto Posto Vitória',
                'image' => 'Auto Posto Vitória.png',
                'description' => null,
                'year' => 2024,
                'order' => 4,
            ],
            [
                'name' => 'Canoagem Xingu',
                'category' => null,
                'company_name' => 'Canoagem Xingu',
                'image' => 'Canoagem Xingu.png',
                'description' => null,
                'year' => 2024,
                'order' => 5,
            ],
            [
                'name' => 'Cardoso',
                'category' => 'Sindimoto',
                'company_name' => 'Sindimoto',
                'image' => 'Cardoso - Sindimoto.png',
                'description' => null,
                'year' => 2024,
                'order' => 6,
            ],
            [
                'name' => 'Castanheira Peças e Serviços',
                'category' => null,
                'company_name' => 'Castanheira Peças e Serviços',
                'image' => 'Castanheira Peças e Serviços.png',
                'description' => null,
                'year' => 2024,
                'order' => 7,
            ],
            [
                'name' => 'CDC Odonto',
                'category' => null,
                'company_name' => 'CDC Odonto',
                'image' => 'CDC Odonto.png',
                'description' => null,
                'year' => 2024,
                'order' => 8,
            ],
        ];

        foreach ($winners as $winnerData) {
            $imagePath = $winnerData['image'];
            $sourcePath = public_path('files/' . $imagePath);
            $destinationPath = 'winners/' . $imagePath;

            // Copia a imagem para o storage se existir
            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($destinationPath, File::get($sourcePath));
                $winnerData['image'] = $destinationPath;
            } else {
                $winnerData['image'] = null;
            }

            Winner::updateOrCreate(
                [
                    'name' => $winnerData['name'],
                    'year' => $winnerData['year'],
                ],
                $winnerData
            );
        }
    }
}
