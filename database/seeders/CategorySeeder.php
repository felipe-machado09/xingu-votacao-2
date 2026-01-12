<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            // Alimentação e Bebidas
            ['name' => 'Restaurante',          'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Pizzaria',             'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Lanche',               'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Churrascaria',         'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Padaria',              'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Delivery de Comida',   'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Cafeteria',            'category_group' => 'Alimentação e Bebidas'],
            ['name' => 'Gás',                  'category_group' => 'Alimentação e Bebidas'],

            // Comércio e Varejo
            ['name' => 'Açougue',                      'category_group' => 'Comércio e Varejo'],
            ['name' => 'Supermercado',                 'category_group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Roupas',               'category_group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Calçados',             'category_group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Eletrodomésticos',      'category_group' => 'Comércio e Varejo'],
            ['name' => 'Eletrônicos',                  'category_group' => 'Comércio e Varejo'],
            ['name' => 'Materiais de Construção',      'category_group' => 'Comércio e Varejo'],
            ['name' => 'Materiais Elétricos',          'category_group' => 'Comércio e Varejo'],
            ['name' => 'Vidraçaria',                   'category_group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Planejado',            'category_group' => 'Comércio e Varejo'],
            ['name' => 'Loja Agropecuária',            'category_group' => 'Comércio e Varejo'],
            ['name' => 'Caça e Pesca',                 'category_group' => 'Comércio e Varejo'],

            // Serviços de Saúde e Bem-estar
            ['name' => 'Clínica Médica',               'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Laboratório',                  'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Clínica Odontológica',         'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Clínica de Olhos',             'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Óptica',                       'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Farmácia de Manipulação',      'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Farmácia e Drogaria',          'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Clínica de Estética',          'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Salão de Beleza',              'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Barbearia',                    'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Academia',                     'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Centro de Treinamento',        'category_group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Pet Shop',                     'category_group' => 'Serviços de Saúde e Bem-estar'],

            // Educação e Cultura
            ['name' => 'Escola',                                  'category_group' => 'Educação e Cultura'],
            ['name' => 'Ensino Superior',                         'category_group' => 'Educação e Cultura'],
            ['name' => 'Curso de Idiomas',                        'category_group' => 'Educação e Cultura'],
            ['name' => 'Centro de Ensino Técnico e Profissionalizante','category_group' => 'Educação e Cultura'],
            ['name' => 'Livraria e Papelaria',                    'category_group' => 'Educação e Cultura'],
            ['name' => 'Escola Ballet',                           'category_group' => 'Educação e Cultura'],
            ['name' => 'Autoescola',                              'category_group' => 'Educação e Cultura'],

            // Transporte e Logística
            ['name' => 'Transporte Escolar e Fretamento',  'category_group' => 'Transporte e Logística'],
            ['name' => 'Táxi e Aplicativo de Transporte',    'category_group' => 'Transporte e Logística'],
            ['name' => 'Locadora de Veículos',               'category_group' => 'Transporte e Logística'],
            ['name' => 'Transportadora',                     'category_group' => 'Transporte e Logística'],
            ['name' => 'Posto de Combustível',               'category_group' => 'Transporte e Logística'],
            ['name' => 'Auto Center',                        'category_group' => 'Transporte e Logística'],
            ['name' => 'Oficina de Motos',                   'category_group' => 'Transporte e Logística'],
            ['name' => 'Loja de Bicicletas',                 'category_group' => 'Transporte e Logística'],
            ['name' => 'Auto Peças',                         'category_group' => 'Transporte e Logística'],
            ['name' => 'Concessionária de Veículos (Carro)', 'category_group' => 'Transporte e Logística'],
            ['name' => 'Concessionária de Veículos (Moto)',  'category_group' => 'Transporte e Logística'],
            ['name' => 'Despachante',                        'category_group' => 'Transporte e Logística'],

            // Entretenimento e Lazer
            ['name' => 'Clube e Parque de Diversão', 'category_group' => 'Entretenimento e Lazer'],
            ['name' => 'Espaço para Eventos e Festas', 'category_group' => 'Entretenimento e Lazer'],
            ['name' => 'Barzinho',                   'category_group' => 'Entretenimento e Lazer'],
            ['name' => 'Casa Noturna',               'category_group' => 'Entretenimento e Lazer'],

            // Serviços Financeiros
            ['name' => 'Instituição Financeira', 'category_group' => 'Serviços Financeiros'],
            ['name' => 'Consignado',             'category_group' => 'Serviços Financeiros'],
            ['name' => 'Contabilidade',          'category_group' => 'Serviços Financeiros'],
            ['name' => 'Imobiliária',            'category_group' => 'Serviços Financeiros'],
            ['name' => 'Loteamento Urbano',      'category_group' => 'Serviços Financeiros'],

            // Serviços de Tecnologia e Comunicação
            ['name' => 'Provedor de Internet',                 'category_group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Assistência Técnica',                  'category_group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Loja de Celulares e Acessórios',         'category_group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Serviço de Segurança Privada',           'category_group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Energia Solar e Energias Renováveis',    'category_group' => 'Serviços de Tecnologia e Comunicação'],

            // Serviços Públicos e Comunitários
            ['name' => 'Sindicato',    'category_group' => 'Serviços Públicos e Comunitários'],
            ['name' => 'Associação',   'category_group' => 'Serviços Públicos e Comunitários'],
        ];

        foreach ($categorias as $categoria) {
            Category::updateOrCreate(
                ['slug' => Str::slug($categoria['name'])],
                [
                    'name' => $categoria['name'],
                    'category_group' => $categoria['category_group'],
                    'slug' => Str::slug($categoria['name']),
                    'description' => 'Vote na melhor empresa desta categoria e ajude a reconhecer a excelência em Altamira.',
                    'is_active' => true,
                    'voting_starts_at' => null,
                    'voting_ends_at' => null,
                ]
            );
        }
    }
}
