<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    protected $categoryNames = [
        // Alimentação
        'Melhor Restaurante',
        'Melhor Lanchonete',
        'Melhor Padaria',
        'Melhor Pizzaria',
        'Melhor Churrascaria',
        'Melhor Sorveteria',
        'Melhor Confeitaria',
        
        // Saúde
        'Melhor Farmácia',
        'Melhor Clínica Médica',
        'Melhor Clínica Odontológica',
        'Melhor Clínica Veterinária',
        'Melhor Laboratório de Análises',
        'Melhor Clínica de Fisioterapia',
        
        // Comércio
        'Melhor Supermercado',
        'Melhor Loja de Roupas',
        'Melhor Loja de Calçados',
        'Melhor Loja de Eletrodomésticos',
        'Melhor Loja de Móveis',
        'Melhor Loja de Materiais de Construção',
        'Melhor Loja de Informática',
        'Melhor Loja de Celulares',
        
        // Serviços
        'Melhor Salão de Beleza',
        'Melhor Barbearia',
        'Melhor Academia',
        'Melhor Auto Peças',
        'Melhor Oficina Mecânica',
        'Melhor Posto de Combustível',
        'Melhor Lavanderia',
        'Melhor Pet Shop',
        
        // Educação
        'Melhor Escola',
        'Melhor Colégio',
        'Melhor Curso de Idiomas',
        'Melhor Cursinho Preparatório',
        
        // Construção
        'Melhor Construtora',
        'Melhor Loja de Materiais de Construção',
        
        // Turismo e Hospedagem
        'Melhor Hotel',
        'Melhor Pousada',
        'Melhor Agência de Viagens',
        
        // Outros
        'Melhor Loja de Bebidas',
        'Melhor Livraria',
        'Melhor Loja de Presentes',
    ];

    protected $descriptions = [
        'Vote na melhor empresa desta categoria e ajude a reconhecer a excelência em Altamira.',
        'Reconheça a qualidade e o compromisso das melhores empresas da nossa cidade.',
        'Sua opinião é importante! Vote na empresa que mais se destaca nesta categoria.',
        'Ajude a escolher os melhores de Altamira através do voto popular.',
        'Participe e vote na empresa que você considera a melhor nesta categoria.',
    ];

    public function definition(): array
    {
        $name = fake()->unique()->randomElement($this->categoryNames);
        $description = fake()->randomElement($this->descriptions);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $description,
            'is_active' => true,
            'voting_starts_at' => null,
            'voting_ends_at' => null,
        ];
    }
}
