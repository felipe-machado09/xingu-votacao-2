<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    protected $companyNames = [
        // Supermercados e Mercados
        'Supermercado Central',
        'Supermercado Bom Preço',
        'Mercado Popular',
        'Supermercado Xingu',
        'Mercado do Povo',
        
        // Farmácias
        'Farmácia Vida',
        'Farmácia Popular',
        'Farmácia Saúde',
        'Farmácia Bem Estar',
        'Drogaria Central',
        
        // Padarias
        'Padaria Pão Quente',
        'Padaria do Bairro',
        'Padaria e Confeitaria',
        'Padaria Central',
        
        // Restaurantes
        'Restaurante Sabor do Norte',
        'Restaurante Churrascaria Gaúcha',
        'Restaurante e Lanchonete',
        'Restaurante Família',
        'Lanchonete do Centro',
        
        // Lojas de Roupas
        'Loja de Roupas Moda Fashion',
        'Loja de Roupas e Acessórios',
        'Boutique Elegance',
        'Loja de Moda Feminina',
        
        // Auto Peças e Oficinas
        'Auto Peças Xingu',
        'Oficina Mecânica Rápida',
        'Auto Peças e Acessórios',
        'Oficina Automotiva',
        
        // Clínicas e Saúde
        'Clínica Médica Saúde Total',
        'Clínica Odontológica Sorriso',
        'Clínica de Fisioterapia',
        'Laboratório de Análises',
        
        // Educação
        'Escola Educação Plus',
        'Colégio Particular',
        'Escola de Idiomas',
        'Cursinho Preparatório',
        
        // Academias e Esportes
        'Academia Fit Life',
        'Academia Body Fitness',
        'Academia Saúde e Forma',
        
        // Pet Shop
        'Pet Shop Amigo Fiel',
        'Pet Shop Cão e Gato',
        'Clínica Veterinária',
        
        // Construção
        'Construtora Horizonte',
        'Loja de Materiais de Construção',
        'Construtora e Incorporadora',
        'Materiais de Construção Xingu',
        
        // Postos e Combustíveis
        'Posto de Combustível Xingu',
        'Posto de Combustível Central',
        'Posto Shell',
        
        // Hotéis e Hospedagem
        'Hotel Pousada do Vale',
        'Hotel e Pousada',
        'Pousada Xingu',
        
        // Beleza
        'Salão de Beleza Glamour',
        'Salão de Beleza e Estética',
        'Barbearia Moderna',
        
        // Eletrônicos e Informática
        'Loja de Eletrodomésticos',
        'Loja de Informática',
        'Loja de Celulares',
        'Assistência Técnica',
        
        // Móveis
        'Loja de Móveis e Decoração',
        'Móveis Planejados',
        'Móveis e Eletro',
        
        // Calçados
        'Loja de Calçados',
        'Sapataria Central',
        'Loja de Calçados e Acessórios',
        
        // Outros
        'Agência de Viagens Turismo Xingu',
        'Loja de Presentes',
        'Livraria e Papelaria',
        'Loja de Bebidas',
    ];

    protected $companyTypes = [
        'LTDA',
        'EIRELI',
        'ME',
        'EPP',
    ];

    public function definition(): array
    {
        $companyName = fake()->unique()->randomElement($this->companyNames);
        $companyType = fake()->randomElement($this->companyTypes);
        $legalName = $companyName . ' ' . $companyType;
        
        // Gerar slug único
        $baseSlug = Str::slug($legalName);
        $slug = $baseSlug;
        $counter = 1;
        
        // Garantir que o slug seja único
        while (\App\Models\Company::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        // Gerar CNPJ válido (formato: 14 dígitos)
        $cnpj = $this->generateCNPJ();
        
        // Gerar telefone no formato brasileiro (11 dígitos com DDD)
        $phone = $this->generatePhone();
        
        return [
            'legal_name' => $legalName,
            'slug' => $slug,
            'email' => fake()->unique()->safeEmail(),
            'cnpj' => $cnpj,
            'responsible_name' => fake()->name(),
            'responsible_phone' => $phone,
            'logo_path' => null,
        ];
    }

    private function generateCNPJ(): string
    {
        // Gera um CNPJ válido (14 dígitos)
        $n1 = fake()->numberBetween(10, 99);
        $n2 = fake()->numberBetween(100, 999);
        $n3 = fake()->numberBetween(100, 999);
        $n4 = fake()->numberBetween(1000, 9999);
        $n5 = fake()->numberBetween(10, 99);
        
        return sprintf('%02d%03d%03d%04d%02d', $n1, $n2, $n3, $n4, $n5);
    }

    private function generatePhone(): string
    {
        // Gera telefone brasileiro (11 dígitos: DDD + número)
        $ddd = fake()->randomElement(['91', '93', '94', '96', '97', '98', '99']);
        $number = fake()->numberBetween(900000000, 999999999);
        
        return $ddd . $number;
    }
}
