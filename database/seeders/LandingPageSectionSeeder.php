<?php

namespace Database\Seeders;

use App\Models\LandingPageSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'hero',
                'title' => 'A votação dos Melhores do Ano 2025 de Altamira já começou!',
                'content' => 'Aqui, prêmio não se compra. Se decide no voto.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'countdown',
                'title' => 'Encerra em',
                'content' => 'DD | HH | MM | SS',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'animated_banner',
                'title' => 'Tarja animada',
                'content' => 'Votos auditados ° Resultado legítimo ° Sem cobranças',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'about',
                'title' => 'Entenda por que este prêmio é diferente.',
                'content' => 'O Melhores do Ano é um prêmio único que reconhece as empresas mais queridas de Altamira através do voto popular. Diferente de outros prêmios, aqui não há jurados, influenciadores ou campanhas pagas.

Na última edição, tivemos mais de 18 mil votos auditados, mais de 200 empresas participantes e mais de 50 segmentos votados. A entrega dos troféus acontece ao vivo no SBT Altamira, garantindo transparência total.

"Aqui, quem escolhe é o povo"',
                'video_url' => 'https://www.youtube.com/embed/sUz34ymF3tI?si=CWGTEXuaPRSXOTLm',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'stats',
                'title' => 'O prêmio que tem credibilidade no mercado',
                'content' => 'Resultado Melhores do Ano 2024',
                'metadata' => [
                    'stat1_number' => '+18 mil',
                    'stat1_text' => 'votos auditados',
                    'stat2_number' => '+200',
                    'stat2_text' => 'empresas participantes',
                    'stat3_number' => '+50',
                    'stat3_text' => 'segmentos votados',
                    'stat4_text' => 'Entrega dos troféus',
                    'stat4_subtext' => 'ao vivo no SBT Altamira',
                ],
                'order' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'winners',
                'title' => 'Melhores do Ano 2024',
                'content' => 'Aqui, o resultado é público, auditável e legítimo.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'key' => 'winners_note',
                'title' => 'Observação',
                'content' => 'As marcas reconhecidas nesta premiação não foram indicadas por jurados, influenciadores ou campanhas pagas. Elas foram escolhidas pela própria população de Altamira.',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'key' => 'sponsors',
                'title' => 'Patrocinadores',
                'content' => '',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'key' => 'faq',
                'title' => 'Perguntas frequentes',
                'content' => '',
                'metadata' => [
                    'faq1_question' => 'Preciso pagar para votar?',
                    'faq1_answer' => 'Não. A votação é gratuita e aberta ao público.',
                    'faq2_question' => 'Quem escolhe as empresas vencedoras?',
                    'faq2_answer' => 'A escolha é popular. Não há jurados, curtidas, comentários ou dinheiro envolvidos. Cada voto é único e registrado.',
                    'faq3_question' => 'Como funciona a votação?',
                    'faq3_answer' => 'Você acessa a plataforma oficial, faz um cadastro rápido e registra seu voto. Os votos são contabilizados em sistema próprio, com critérios públicos.',
                    'faq4_question' => 'Quando e onde os vencedores serão divulgados?',
                    'faq4_answer' => 'O resultado será anunciado nas redes sociais da Vale do Xingu (@valedoxingusbt) e no telejornal SBT Altamira.',
                    'faq5_question' => 'Como acontece a entrega dos troféus?',
                    'faq5_answer' => 'A entrega é ao vivo no SBT Altamira, com cobertura jornalística e legitimidade pública.',
                    'faq6_question' => 'Por que não existe ranking parcial?',
                    'faq6_answer' => 'Para evitar influência. O resultado é divulgado apenas no encerramento oficial da votação.',
                ],
                'order' => 9,
                'is_active' => true,
            ],
            [
                'key' => 'footer',
                'title' => 'Rodapé',
                'content' => '',
                'metadata' => [
                    'company_name' => 'Rede de Rádio e Televisão Vale do Xingu LTDA',
                    'cnpj' => '22.918.262/0001-72',
                    'terms_url' => 'https://docs.google.com/document/d/16IrkfQSGd5rk97X5R-r-R74TPdJCfENitea2ezeBZvY/edit?usp=sharing',
                    'regulation_url' => 'https://docs.google.com/document/d/11hnt8qM9lkDf_YpMk1kgHOCBCfHRWclruEJ46cSrA5Q/edit?usp=sharing',
                    'lgpd_url' => 'https://melhores.valedoxingu.com.br/documents/LGPD_Melhores.pdf',
                    'facebook_url' => 'https://www.facebook.com/SBTALTAMIRA10',
                    'instagram_url' => 'https://www.instagram.com/valedoxingusbt/',
                    'youtube_url' => 'https://www.youtube.com/@valedoxingusbt10',
                    'tiktok_url' => 'https://www.tiktok.com/@valedoxingusbt',
                    'whatsapp_url' => 'https://wa.link/jpkt1t',
                ],
                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            LandingPageSection::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }
}
