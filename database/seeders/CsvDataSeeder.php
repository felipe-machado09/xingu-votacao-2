<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CsvDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📂 Importando categorias...');
        $this->seedCategories();

        $this->command->info('📂 Importando empresas e vinculando às categorias...');
        $this->seedCompanies();

        $this->command->info('✅ Importação concluída!');
    }

    private function seedCategories(): void
    {
        $categorias = [
            // Serviços de Saúde e Bem-estar
            ['name' => 'Academia', 'description' => 'Espaço para prática de exercícios físicos e cuidado com a saúde.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Barbearia', 'description' => 'Estabelecimento voltado ao corte de cabelo e cuidados masculinos.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Clínica de Estética e Bem-estar', 'description' => 'Espaço dedicado a tratamentos estéticos e cuidados pessoais.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Clínica Médica', 'description' => 'Unidade que oferece consultas e atendimentos médicos.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Clínica Odontológica', 'description' => 'Clínica especializada em cuidados com a saúde bucal.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Farmácia de Manipulação', 'description' => 'Estabelecimento que produz medicamentos conforme prescrição médica.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Farmácia e Drogaria', 'description' => 'Comércio de medicamentos e produtos de saúde.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Laboratório', 'description' => 'Unidade que realiza exames clínicos e laboratoriais.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Ótica', 'description' => 'Estabelecimento especializado em óculos e cuidados com a visão.', 'group' => 'Serviços de Saúde e Bem-estar'],
            ['name' => 'Salão de Beleza', 'description' => 'Espaço que oferece serviços de cabelo, unhas e estética.', 'group' => 'Serviços de Saúde e Bem-estar'],

            // Alimentação e Bebidas
            ['name' => 'Açougue', 'description' => 'Estabelecimento que vende carnes e cortes para o consumo diário.', 'group' => 'Alimentação e Bebidas'],
            ['name' => 'Cafeteria', 'description' => 'Local especializado em cafés, bebidas e acompanhamentos.', 'group' => 'Alimentação e Bebidas'],
            ['name' => 'Churrascaria', 'description' => 'Restaurante especializado em carnes assadas e churrasco.', 'group' => 'Alimentação e Bebidas'],
            ['name' => 'Delivery de Comida', 'description' => 'Serviço de entrega de refeições prontas.', 'group' => 'Alimentação e Bebidas'],
            ['name' => 'Pizzaria', 'description' => 'Estabelecimento especializado na produção e venda de pizzas.', 'group' => 'Alimentação e Bebidas'],
            ['name' => 'Restaurante', 'description' => 'Estabelecimento que oferece refeições completas.', 'group' => 'Alimentação e Bebidas'],
            ['name' => 'Sorveteria', 'description' => 'Estabelecimento especializado na venda de sorvetes e sobremesas geladas.', 'group' => 'Alimentação e Bebidas'],

            // Comércio e Varejo
            ['name' => 'Loja Agropecuária e Insumos Agrícolas', 'description' => 'Estabelecimento que vende produtos voltados ao setor rural.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Caça e Pesca', 'description' => 'Loja especializada em artigos para pesca e atividades no campo.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Calçados', 'description' => 'Estabelecimento especializado na venda de calçados.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Celulares e Acessórios', 'description' => 'Comércio de celulares e acessórios para telefonia.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Eletrodomésticos', 'description' => 'Loja que comercializa eletrodomésticos e eletrônicos residenciais.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Material Elétrico', 'description' => 'Comércio especializado em materiais para instalações elétricas.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Loja de Roupas', 'description' => 'Comércio especializado na venda de roupas e acessórios.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Materiais de Construção', 'description' => 'Loja especializada em produtos para obras e reformas.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Perfumaria e Cosméticos', 'description' => 'Comércio de perfumes, maquiagem e produtos de beleza.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Pet Shop', 'description' => 'Loja de produtos e serviços para animais de estimação.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Supermercado', 'description' => 'Estabelecimento com variedade de produtos alimentícios e domésticos.', 'group' => 'Comércio e Varejo'],
            ['name' => 'Vidraçaria', 'description' => 'Empresa especializada em serviços e produtos de vidro.', 'group' => 'Comércio e Varejo'],

            // Educação e Cultura
            ['name' => 'Autoescola', 'description' => 'Empresa especializada na formação de condutores e no processo de habilitação.', 'group' => 'Educação e Cultura'],
            ['name' => 'Colégio Particular', 'description' => 'Escola privada de educação infantil, fundamental ou médio.', 'group' => 'Educação e Cultura'],
            ['name' => 'Ensino Superior', 'description' => 'Instituição que oferece cursos de graduação.', 'group' => 'Educação e Cultura'],
            ['name' => 'Livraria e Papelaria', 'description' => 'Loja de livros, materiais escolares e itens de escritório.', 'group' => 'Educação e Cultura'],

            // Transporte e Logística
            ['name' => 'Auto Center', 'description' => 'Centro automotivo que oferece revisão, manutenção e troca de peças.', 'group' => 'Transporte e Logística'],
            ['name' => 'Auto Peças', 'description' => 'Loja especializada na venda de peças e acessórios para veículos.', 'group' => 'Transporte e Logística'],
            ['name' => 'Concessionária de Carros', 'description' => 'Empresa autorizada na venda de carros novos ou seminovos.', 'group' => 'Transporte e Logística'],
            ['name' => 'Concessionária de Motos', 'description' => 'Empresa autorizada na venda de motocicletas novas ou seminovas.', 'group' => 'Transporte e Logística'],
            ['name' => 'Empresa de Vistoria Veicular', 'description' => 'Estabelecimento que realiza inspeção e vistoria de veículos.', 'group' => 'Transporte e Logística'],
            ['name' => 'Oficina de Motos', 'description' => 'Empresa que realiza manutenção e conserto de motocicletas.', 'group' => 'Transporte e Logística'],
            ['name' => 'Posto de Combustível', 'description' => 'Empresa que realiza abastecimento de veículos.', 'group' => 'Transporte e Logística'],
            ['name' => 'Táxi e Aplicativo de Transporte', 'description' => 'Serviço de transporte individual de passageiros.', 'group' => 'Transporte e Logística'],

            // Serviços Financeiros
            ['name' => 'Crédito Consignado', 'description' => 'Empresa que oferece empréstimo com desconto direto em folha ou benefício.', 'group' => 'Serviços Financeiros'],

            // Serviços de Tecnologia e Comunicação
            ['name' => 'Assistência Técnica (celular e eletrônicos)', 'description' => 'Empresa que realiza conserto e manutenção de celulares e aparelhos eletrônicos.', 'group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Energia Solar', 'description' => 'Empresa especializada na instalação de sistemas de energia solar.', 'group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Provedor de Internet', 'description' => 'Empresa que fornece serviço de internet residencial ou empresarial.', 'group' => 'Serviços de Tecnologia e Comunicação'],
            ['name' => 'Segurança Privada', 'description' => 'Empresa especializada em vigilância e proteção patrimonial.', 'group' => 'Serviços de Tecnologia e Comunicação'],
        ];

        $count = 0;
        foreach ($categorias as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name'             => $cat['name'],
                    'slug'             => Str::slug($cat['name']),
                    'description'      => $cat['description'],
                    'category_group'   => $cat['group'],
                    'is_active'        => true,
                    'voting_starts_at' => null,
                    'voting_ends_at'   => null,
                ]
            );
            $count++;
        }

        $this->command->info("   ✅ {$count} categorias criadas/atualizadas.");
    }

    private function seedCompanies(): void
    {
        // [Categoria, NomeFantasia, CEP, Instagram, WhatsApp]
        $empresas = [
            // ── Academia ──
            ['Academia', 'MoovIN - Treinamento Físico Funcional', '68371102', '@moovin.ctf', '93992435238'],
            ['Academia', 'Flex Academia', '68371055', '@flexacademiaa', '93991188944'],
            ['Academia', 'InterFit Altamira', '68372853', '@interfitatm', '93933004646'],
            ['Academia', 'Top Academia', '68372210', '@topacademiaatm', '93991441000'],
            ['Academia', 'RevLife Academia', '68372222', '@revlifee', '93991001044'],
            ['Academia', 'Corpus Academia', '68372191', '@corpusaltamira', '93991279533'],
            ['Academia', 'RC Academia', '68372222', '@rcacademia_atm', '93991345223'],
            ['Academia', 'Ratto Fitness Academia', '68375049', '@academia_rattofitness', '93991894969'],

            // ── Açougue ──
            ['Açougue', 'Altamira Carnes', '68372577', '@altamiracranesoficial', '93991792188'],
            ['Açougue', 'Açougue Central Mutirão', '68371058', '@acouguecentralatm.123', '93992062488'],
            ['Açougue', 'Açougue das Estrelas', '68372823', '@acouguedasestrelas', '93991475746'],
            ['Açougue', 'O Rei das Carnes', '68371028', '@REI_DASCARNES2', '93992918820'],
            ['Açougue', 'Tavares Carnes e Frios', '68372833', '@tavarescarnesefrios', '93991348757'],

            // ── Assistência Técnica (celular e eletrônicos) ──
            ['Assistência Técnica (celular e eletrônicos)', 'Pimenta Cell', '68371000', '@pimentacell1', '93992198044'],
            ['Assistência Técnica (celular e eletrônicos)', 'Consert Cell', '68371000', '@consert_cell_atm', '93992198044'],
            ['Assistência Técnica (celular e eletrônicos)', 'Mr Tech Altamira', '68371163', '@mrtechatm', '93991138875'],
            ['Assistência Técnica (celular e eletrônicos)', 'Davi Cell', '68371125', '@_loja_davicell', '93992251615'],
            ['Assistência Técnica (celular e eletrônicos)', 'Dr. Reparo Smart', '68371159', '@dr.reparosmart', '93991250886'],

            // ── Auto Center ──
            ['Auto Center', 'Castanheira Peças & Serviços', '68371970', '@castanheirapecaseservicos', '9335153443'],
            ['Auto Center', 'MultCenter Peças e serviços', '68372855', '@multcenteratm', '93992192703'],
            ['Auto Center', 'HL Auto Center', '68372095', '@hlautocenter_oficial', '93992224833'],
            ['Auto Center', 'MK Auto Peças', '68373500', '@mkautopecasatm', '93991736275'],
            ['Auto Center', 'MITTOY Autopeças e Mecânica', '68372618', '@mittoy_autopecas', '93991574812'],
            ['Auto Center', 'Lorenzoni Auto Center', '68371262', '@lorenzoniautocenter', '93988095173'],
            ['Auto Center', 'SL Pneus', '68372095', '@slpneusautocenter', '93992442206'],
            ['Auto Center', 'Auto Center Campioni', '68371970', '@autocentercampioni', '9335153782'],
            ['Auto Center', 'Brasil Auto Center', '68372070', '@brasilautocenteratm', '93992415263'],
            ['Auto Center', 'Panda Auto Center', '68372590', '@pandaautocenter_', '9335154015'],
            ['Auto Center', 'JF Auto Center', '68375343', '@jfpneus_filial1', '93992145447'],

            // ── Auto Peças ──
            ['Auto Peças', 'Castanheira Peças & Serviços', '68371970', '@castanaheirapecaseservicos', '9335153443'],
            ['Auto Peças', '2 Coelhos Auto Peças', '68373500', '@2coelhosauto', '93991424043'],
            ['Auto Peças', 'Castrillon Auto Peças', '68374274', '@castrillonpecaseservicos', '93984049806'],
            ['Auto Peças', 'Central Auto Peças', '68370001', '@centralcomerciodeautopecas', '93991815846'],
            ['Auto Peças', 'TV Auto Peças', '68371105', '@sena.altamira', '93991013896'],
            ['Auto Peças', 'Sena Auto Peças', '68371105', '@sena.altamira', '93991013896'],
            ['Auto Peças', 'Mercadão das Peças', '68372222', '@mercadaoatm', '93991199905'],
            ['Auto Peças', 'Xingu Peças e Serviços', '68372573', '@autopecasxingu', '93991358007'],
            ['Auto Peças', 'PMZ', '68374274', '@pemazapecas', '9335027600'],

            // ── Autoescola ──
            ['Autoescola', 'Autoescola Xingu', '68372005', '@autoescolaxingu', '93991626677'],
            ['Autoescola', 'CFC Líder', '68372290', '@cfclider_', '93991356323'],
            ['Autoescola', 'CFC Puma', '68372574', '@pumaautoescola', '93991371455'],
            ['Autoescola', 'CFC Altamira', '68372856', '@cfcaltamira', '93991384153'],
            ['Autoescola', 'Autoescola Campeã', '68372856', '@autoescolacampeao', '93991926632'],
            ['Autoescola', 'Autoescola New', '68372856', '@autoescolanew', '93991738415'],
            ['Autoescola', 'Autoescola Castro', '68372856', '@autoescolacastroo', '93991384153'],

            // ── Barbearia ──
            ['Barbearia', 'Mãos de Tesoura', '68371025', '@maosdetesourabarbearia', '93991613987'],
            ['Barbearia', 'Master Barbearia', '68371000', '@masterbarbeariatm', '93991311488'],
            ['Barbearia', 'Barbearia Rota 230', '68372020', '@barbeariarota230', '93992333126'],
            ['Barbearia', 'Da Matta Barbearia', '68371000', '@damatta__barbearia', '93991394617'],
            ['Barbearia', 'Barbearia Magrão', '68371432', '@magrao_barber_atm', '93991137076'],
            ['Barbearia', 'Gold Razor Barber Shop', '68371275', '@goldrazorbarbeshop', '93991202843'],
            ['Barbearia', 'Maycon Barber', '68371057', '@mayconbarberatm', '93991260777'],

            // ── Cafeteria ──
            ['Cafeteria', 'Café & Companhia', '68372170', '@cafeecompanhiaaltamira', '93991717070'],
            ['Cafeteria', 'Pop Coffe Altamira', '68372574', '@popcoffeealtamira', '62999471757'],
            ['Cafeteria', 'Cheirinho Bão Altamira', '68372005', '@cheirinbaoaltamira', '93992216339'],

            // ── Churrascaria ──
            ['Churrascaria', 'Churrascaria Boi na Brasa', '68374274', '@boinabrasaatm', '93992098929'],
            ['Churrascaria', 'Churrascaria Braseiro', '68372570', '@braseiro_bar', '93991462170'],
            ['Churrascaria', 'Gosto Real Churrascaria', '68372040', '@gostorealchurrascaria', '93991269207'],
            ['Churrascaria', 'Churrascaria Casa Nova', '68371195', '@churrascaria_casanova', '93988113115'],
            ['Churrascaria', 'Restaurante e Churrascaria Tempero Brasileiro', '68372855', '@temperobrasileiroatm', '93991180707'],
            ['Churrascaria', 'Quintal do Tião', '68378020', '@quintaldotiao', '93991024064'],

            // ── Clínica de Estética e Bem-estar ──
            ['Clínica de Estética e Bem-estar', 'Adriana Rezende Estética', '68372210', '@adrianarezendeestetica', '93991715143'],
            ['Clínica de Estética e Bem-estar', 'Jake Rodrigues Estética', '68372005', '@jakerodriguesestetica_', '93991528635'],
            ['Clínica de Estética e Bem-estar', 'Revitalize Estética Especializada', '68371456', '@revitalize.atm', '93992183219'],
            ['Clínica de Estética e Bem-estar', 'Elleve-se Estética Avançada', '68371276', '@elleveseestetica', '93991206466'],
            ['Clínica de Estética e Bem-estar', 'Sublime Estética', '68373090', '@sublimeatm', '93991941416'],
            ['Clínica de Estética e Bem-estar', 'Corpo Bueno Altamira', '68372005', '@corpobuenoaltamira', '93991970964'],
            ['Clínica de Estética e Bem-estar', 'La Vie Estética e Terapias', '68371057', '@lavie_esteticaeterapias', '93991440818'],

            // ── Clínica Médica ──
            ['Clínica Médica', 'Clínica da Família', '68371159', '@clinicadafamilia_altamira', '93991823767'],
            ['Clínica Médica', 'Clínica Laboclin - Centro Integrado de Diagnóstico', '68372833', '@laboclinclinica', '93991561378'],
            ['Clínica Médica', 'Diagmed', '68371456', '@diagmed_atm', '93992214828'],
            ['Clínica Médica', 'CDC Saúde', '68371105', '@grupocdc_saude', '93991492800'],
            ['Clínica Médica', 'Humani Clínica Especializada', '68371155', '@humaniclinica_altamira', '93992255110'],
            ['Clínica Médica', 'Clínica Médica Maxxi Saúde', '68371113', '@maxxi.saude', '93991801155'],
            ['Clínica Médica', 'Clínica Cemear Saúde', '68371040', '@ccemear', '93992153649'],
            ['Clínica Médica', 'Pilar Clínica', '68371274', '@pilar_clinica', '93992228000'],
            ['Clínica Médica', 'Viver Centro de Saúde', '68371271', '@vivercentrodesaude', '93992453703'],
            ['Clínica Médica', 'Evoluir Centro Médico', '68372880', '@evoluircentromedico', '93991962002'],
            ['Clínica Médica', 'Clínica Ideally', '68372191', '@clinica.ideally', '93992167400'],
            ['Clínica Médica', 'Clínica Orogastro Saúde', '68371025', '@clinicaorogastro', '93991203400'],

            // ── Clínica Odontológica ──
            ['Clínica Odontológica', 'Dr Felipe Sena', '', '@dr.felipesena', '91982245813'],
            ['Clínica Odontológica', 'Instituto Leão Odontologia e Estética Avançada', '68372222', '@institutoleao', '93991374737'],
            ['Clínica Odontológica', 'Iaso Odontologia', '68376720', '@iasodontologia', '93991008681'],
            ['Clínica Odontológica', 'Dr. Luciano Madruga - Bucomaxilo', '68372000', '@drlucianomadruga', '93991380691'],
            ['Clínica Odontológica', 'CDC Odonto', '68371105', '@grupocdc_odonto', '93992073152'],
            ['Clínica Odontológica', 'Odonto Vida', '68371005', '@odonto_vida_odontologia', '93991737410'],
            ['Clínica Odontológica', 'Odonto Clin', '68377590', '@odontoclin_atm', '93991065802'],
            ['Clínica Odontológica', 'Clínica NIS', '68372090', '@clinica.nis', '93991732333'],
            ['Clínica Odontológica', 'Coife Odonto', '68371272', '@coifealtamira', '93992408297'],
            ['Clínica Odontológica', 'Pop Dents', '68371432', '@popdentsaltamira', '91991328254'],
            ['Clínica Odontológica', 'Viver Odontologia', '68371271', '@viverodontologiaoficial', '93992453703'],

            // ── Colégio Particular ──
            ['Colégio Particular', 'Centro Educacional Paraíso do Saber', '68375049', '@paraisodosaberatm', '93992273286'],
            ['Colégio Particular', 'Colégio Evolução', '68371288', '@evolucaoatm', '93991905760'],
            ['Colégio Particular', 'Colégio Adventista de Altamira', '68374000', '@colegioadventistaaltamira', '93988148798'],
            ['Colégio Particular', 'Colégio Objetivo Sapiens', '68372040', '@colegio_objetivo_sapiens', '93992163333'],
            ['Colégio Particular', 'Colégio Gildete Dutra', '68372310', '@colegiogildetedutra', '93992153188'],
            ['Colégio Particular', 'Centro de Estudos Anchieta', '68371486', '@centrodeestudosanchieta', '93991988044'],
            ['Colégio Particular', 'Escolinha da Mônica', '68371113', '', ''],

            // ── Concessionária de Carros ──
            ['Concessionária de Carros', 'Chevrolet Rio Norte', '68372095', '@rionortealtamira', '93991862791'],
            ['Concessionária de Carros', 'BYD Altamira', '68372000', '@byd.altamira', '93991257501'],
            ['Concessionária de Carros', 'Rio Veículos', '68372574', '@rioveiculosatm', '93991275569'],
            ['Concessionária de Carros', 'Altavei', '68372100', '@altaveivw', '93992074111'],
            ['Concessionária de Carros', 'Mônaco Veículos', '68372095', '@monacoveiculosnorte', '8000003148'],
            ['Concessionária de Carros', 'ATM Veículos', '68371432', '@atm_veiculos', '93991803774'],

            // ── Concessionária de Motos ──
            ['Concessionária de Motos', 'Yamaha Altamira', '68371000', '@yamahaaltamira', '93991812006'],
            ['Concessionária de Motos', 'Xingu Motos (Honda)', '68373500', '@hondaxingumotos', '93991351100'],
            ['Concessionária de Motos', 'Shineray Altamira', '68372191', '@shineray.altamira', '93981062798'],
            ['Concessionária de Motos', 'Bull Motors Altamira', '68373020', '@bullmotorsaltamira', '93988121614'],
            ['Concessionária de Motos', 'AvelloZ', '', '@avellozaltamirapa', '93992062793'],

            // ── Crédito Consignado ──
            ['Crédito Consignado', 'Fácil Cred', '68371970', '@facilcredialtamira', '93991691662'],
            ['Crédito Consignado', 'AmazonCred', '68371163', '@amazoncred', '93991057163'],
            ['Crédito Consignado', 'Xingu Financeira', '', '@xingu.financeira', ''],
            ['Crédito Consignado', 'Du Norte Cred', '', '', ''],
            ['Crédito Consignado', 'W&J Cred', '68371423', '@globovifracaria', '93991615420'],
            ['Crédito Consignado', 'Agibank', '68371000', '@agi_altamira', '8007300999'],

            // ── Empresa de Vistoria Veicular ──
            ['Empresa de Vistoria Veicular', 'GP Vistoria Veicular', '68372855', '@gpvistoria_altamira', '93991114012'],
            ['Empresa de Vistoria Veicular', 'Super Visão Vistoria Automotiva', '68372290', '@vistoria_automotiva_atm', '93991727010'],

            // ── Energia Solar ──
            ['Energia Solar', 'Fausg Eletro Solar', '68371163', '@fausg.eletro.solar', '93992082113'],
            ['Energia Solar', 'Solluz Energia', '68372566', '@solluzenergiaatm', '93999769794'],
            ['Energia Solar', 'Help Energia Solar', '68372191', '@helpenergia', '93991383737'],
            ['Energia Solar', 'Kasa Solar', '68371028', '@kasasolarxingu', '93992071483'],
            ['Energia Solar', 'Park Engenharia Energia Solar', '68371295', '@park.energiasolar', '93991600177'],
            ['Energia Solar', 'Altaseg', '68371274', '@altasegoficial', '93992182871'],

            // ── Ensino Superior ──
            ['Ensino Superior', 'Centro Universitário Internacional Uninter', '68371291', '@uninter_altamira', '93991279009'],
            ['Ensino Superior', 'Faculdade Serra Dourada', '', '@serradourada', '31996268077'],
            ['Ensino Superior', 'FACX', '68371040', '@facx_altamira', '93991968181'],
            ['Ensino Superior', 'Unopar', '68372222', '@unopar_altamira', '93999020866'],
            ['Ensino Superior', 'Uniplan', '68371075', '@uniplan_altamira', '93992259826'],
            ['Ensino Superior', 'Estácio', '68371456', '@estaciocentroaltamira', '93991008695'],
            ['Ensino Superior', 'Uninassau', '68372855', '@uninassau.altamira', '9381197467'],
            ['Ensino Superior', 'Fametro', '68371000', '@fametropoloalatamira', '93991385103'],

            // ── Farmácia de Manipulação ──
            ['Farmácia de Manipulação', 'Natural Farma', '68371000', '@naturalfarma', '93991256269'],
            ['Farmácia de Manipulação', 'Pharmapele Altamira', '68371025', '@pharmapele_altamira', '93992331972'],
            ['Farmácia de Manipulação', 'Bio Farma', '68371163', '@biofarmaaltamira', '93991692355'],
            ['Farmácia de Manipulação', 'Bio Fórmula', '68371025', '@bioformulaaltamira', '93991611060'],

            // ── Farmácia e Drogaria ──
            ['Farmácia e Drogaria', 'Drogaria Tonhão', '68371432', '@drogariatonhao', '93991079067'],
            ['Farmácia e Drogaria', 'Farmácia Lacerda', '68371294', '@farmacialacerdaa', '93991889117'],
            ['Farmácia e Drogaria', 'CDC Farma', '68371105', '@grupocdc_farma', '93991521682'],
            ['Farmácia e Drogaria', 'Droga Minas Brasília', '68375080', '@drogaminasbrasilia', '93991441036'],
            ['Farmácia e Drogaria', 'Rede Inova Drogarias', '68371294', '@dg.inovamix', '93991001337'],
            ['Farmácia e Drogaria', 'Drogarias Bem Econômica', '68371105', '@bemeconomica.altamira', '93991002050'],
            ['Farmácia e Drogaria', 'Ultra Popular', '68371163', '@ultrapopulargrupopara', '93991083096'],
            ['Farmácia e Drogaria', 'Viver Farmácia', '68371163', '@viverfarmaciaoficial', '93991303703'],

            // ── Laboratório ──
            ['Laboratório', 'Laboratório Popular de Altamira', '68371163', '@laboratorioaltamira', '93992217653'],
            ['Laboratório', 'Laboratório Central de Altamira', '68372590', '@laboratoriolcaaltamira', '93991729401'],
            ['Laboratório', 'Laboratório Confiança', '68371025', '@laboratorioconfianca', '9335152299'],
            ['Laboratório', 'Viver Laboratório', '68371271', '@vivercentrodesaude', '93992453703'],
            ['Laboratório', 'Laboratório Exatus Altamira', '68371001', '@laboratorioexatus.atm', '93991305483'],
            ['Laboratório', 'Laboclin', '68372833', '@laboclinclinica', '93991884757'],

            // ── Livraria e Papelaria ──
            ['Livraria e Papelaria', 'Ecoplante-Papelaria Sustentável', '68371040', '@ecoplante_papelplantavel', '93991097508'],
            ['Livraria e Papelaria', 'Papel & CIA', '68372095', '@papelecia.atm', '93991460868'],
            ['Livraria e Papelaria', 'Hadassa', '68371155', '@papelariahadassa', '93991817227'],
            ['Livraria e Papelaria', 'A Colegial', '68371163', '@acolegial.oficial', '93999011750'],
            ['Livraria e Papelaria', 'Ponto & Vírgula', '68372060', '@pontoevirgulaatm', '93991353778'],
            ['Livraria e Papelaria', 'Canetas e Rabiscos', '68371000', '@canetaserabiscos', '93981112940'],
            ['Livraria e Papelaria', 'TC Papelaria e Informática', '68372547', '@tcpapelaria_altamira', '93992275853'],
            ['Livraria e Papelaria', 'Modelo Papelaria e Gráfica', '68372567', '@modeloatm', '93991553752'],

            // ── Loja Agropecuária e Insumos Agrícolas ──
            ['Loja Agropecuária e Insumos Agrícolas', 'AgroShop Altamira', '68372210', '@agroshop.atm', '93991652211'],
            ['Loja Agropecuária e Insumos Agrícolas', 'Selaria Mineira', '68372095', '@selariamineiraoficial', '93991810797'],
            ['Loja Agropecuária e Insumos Agrícolas', 'Intergrãos Altamira', '68373500', '@intergraosaltamira', '93984088773'],
            ['Loja Agropecuária e Insumos Agrícolas', 'AgroAmazônia', '68376137', '@agroamazonia', '93992186198'],
            ['Loja Agropecuária e Insumos Agrícolas', 'AgroSanta', '68372100', '@agro_santa', '93991073622'],
            ['Loja Agropecuária e Insumos Agrícolas', 'Primavera Agropecuária', '68371000', '@primaveraagropecuariaatm', '93991621670'],
            ['Loja Agropecuária e Insumos Agrícolas', 'Amigos do Campo', '68148000', '@amigosdocampo_oficial', '93991244455'],
            ['Loja Agropecuária e Insumos Agrícolas', 'Parafusão Agropecuária', '68373000', '@parafusaoagropecuaria', '93991271015'],
            ['Loja Agropecuária e Insumos Agrícolas', 'Agroquima', '68374276', '@agroquima', '9331910441'],

            // ── Loja de Caça e Pesca ──
            ['Loja de Caça e Pesca', 'Cia Da Pesca', '68371432', '@ciadapescaatm', '93992054381'],
            ['Loja de Caça e Pesca', 'Pontal Pesca', '68371432', '@ciadapescaatm', '93992054381'],
            ['Loja de Caça e Pesca', 'Xingu Pesca', '68371000', '@xingupesca', '9335152501'],
            ['Loja de Caça e Pesca', 'Panda Pesca e Camping', '68371274', '@pandapescaecamping', '93991302010'],

            // ── Loja de Calçados ──
            ['Loja de Calçados', 'Usaflex Altamira', '68371432', '@usaflexaltamira', '93991682040'],
            ['Loja de Calçados', 'Sou Musa', '68372005', '@soumusaatm', '93991090413'],
            ['Loja de Calçados', 'Tok do Pé', '68371163', '@tokdopecalcados', '93991488200'],
            ['Loja de Calçados', 'Pé com Pé', '68371000', '@pecompeoficial', '93999027311'],
            ['Loja de Calçados', 'Sapatinho de Luxo', '68371000', '@sapatinhodeluxo', '51989863651'],
            ['Loja de Calçados', 'Bel Modas Sports', '68371125', '@belmodasatm', '93991177311'],
            ['Loja de Calçados', 'Container do Pé', '68371000', '@containerstorealtamira', '93999027311'],
            ['Loja de Calçados', 'Clutch', '68371400', '@clutchatm', '93991351949'],
            ['Loja de Calçados', 'Santa Lolla', '68371486', '@santalolla_altamira', '93991521200'],
            ['Loja de Calçados', 'Arezzo', '68371105', '@arezzoaltamira', '93991626565'],
            ['Loja de Calçados', 'Colcci Altamira', '68371432', '@colccialtamira', '93992262555'],
            ['Loja de Calçados', 'Maruzi Altamira', '68371000', '@maruzisportaltamira', '93991533720'],

            // ── Loja de Celulares e Acessórios ──
            ['Loja de Celulares e Acessórios', 'Altamira Imports', '68371041', '@altamiraimports_', '93992241482'],
            ['Loja de Celulares e Acessórios', 'JL Cell', '68372110', '@jlcel', '93991101224'],
            ['Loja de Celulares e Acessórios', 'Pimenta Cell', '68371000', '@pimentacell1', '93992198044'],
            ['Loja de Celulares e Acessórios', 'Smart7', '68371125', '@smart7altamira', '93992053248'],
            ['Loja de Celulares e Acessórios', 'Gringo Imports', '68375049', '@gringo_importsatm', '93988078924'],
            ['Loja de Celulares e Acessórios', 'TCImports', '68371163', '@tcimports.altamira', '93991564645'],
            ['Loja de Celulares e Acessórios', 'Lion Conceito', '68372222', '@lion.conceito', '93991275566'],
            ['Loja de Celulares e Acessórios', 'Usafone Celulares', '68371125', '@usafone.altamira', '93992155947'],
            ['Loja de Celulares e Acessórios', 'ShopCell', '68371163', '@shopcell.atm', '93991745811'],
            ['Loja de Celulares e Acessórios', 'Centro Cell', '68371157', '@centrocellaltamira02', '93992086229'],
            ['Loja de Celulares e Acessórios', 'Apolo Comércio', '68372222', '@apolo.comercio', '93996541450'],
            ['Loja de Celulares e Acessórios', 'Dr7', '68371000', '@dr7altamira', '93991097730'],

            // ── Loja de Eletrodomésticos ──
            ['Loja de Eletrodomésticos', 'Leleu Eletro', '68371028', '@leleu_eletro.altamira', '93992255875'],
            ['Loja de Eletrodomésticos', 'Eletro Mateus', '68371432', '@eletromateusoficial', '9821083535'],
            ['Loja de Eletrodomésticos', 'Gazin', '68371000', '@gazin_atm', '93991961935'],
            ['Loja de Eletrodomésticos', 'Magazine Liliani', '68371000', '@nagazineliliane', '93992428830'],
            ['Loja de Eletrodomésticos', 'Loja Centro', '68371250', '@lojacentroofc', ''],
            ['Loja de Eletrodomésticos', 'Facilar Altamira', '68371000', '@facilar_altamira', '93991960938'],
            ['Loja de Eletrodomésticos', 'Lar Brasil', '68371000', '@lojaslarbrasil', '9335154370'],
            ['Loja de Eletrodomésticos', 'A Movelar', '68371000', '@amovelaratm', '93991080810'],
            ['Loja de Eletrodomésticos', 'Eletromarc', '68372590', '@eletromar_', '93981176791'],
            ['Loja de Eletrodomésticos', 'Timbo Eletro', '68371090', '@tiboeletroatm', '93991989073'],
            ['Loja de Eletrodomésticos', 'Center Lar', '68371125', '@centerlaratm', '93991186528'],
            ['Loja de Eletrodomésticos', 'Nova Utilar', '68371000', '@nova_utilar', '93991534802'],

            // ── Loja de Material Elétrico ──
            ['Loja de Material Elétrico', 'Teto Materiais Elétricos', '68372833', '@tetoaltamira', '93991271856'],
            ['Loja de Material Elétrico', 'Circuito Materiais Elétricos', '68371085', '@circuitomateriaiseletretricos', '93991969690'],
            ['Loja de Material Elétrico', 'A Elétrica', '68371163', '@aeletricaatm', '93981125080'],
            ['Loja de Material Elétrico', 'Eletrocon Materiais Elétricos', '68371294', '@eletrocon', '93991156263'],
            ['Loja de Material Elétrico', 'Cabine Média Tensão', '68373102', '@cabinemediatensao', '93996521023'],

            // ── Loja de Roupas ──
            ['Loja de Roupas', 'Alta Modas', '', '@alta_modasatm', '93982407763'],
            ['Loja de Roupas', 'Lojas Ravena', '68371294', '@lojasravena_atm', '93988030951'],
            ['Loja de Roupas', 'Rede Tudo 20', '68371163', '@redetudo.altamira', '93992150700'],
            ['Loja de Roupas', 'Voga', '68371057', '@vogaloja', '93992477433'],
            ['Loja de Roupas', 'Chambel', '68371125', '@chambelaltamira', '9335153487'],
            ['Loja de Roupas', 'Manchete', '68140000', '@manchetealtamira', '93991733079'],
            ['Loja de Roupas', 'Avenida', '68371000', '@avenida.altamira', '65996751225'],
            ['Loja de Roupas', 'Ideal Magazine', '68371125', '@idealmagazineatm', '93991785405'],
            ['Loja de Roupas', 'Imperial Modas', '68371000', '@imperial.altamira', '9335156988'],
            ['Loja de Roupas', 'Malwee Altamira', '68371432', '@malweealtamira', '93991125984'],
            ['Loja de Roupas', 'TXC Altamira', '68372110', '@txc.altamira', '93992135373'],
            ['Loja de Roupas', 'Container Store', '68372110', '@container.store', '9399872337'],
            ['Loja de Roupas', 'Nalu Boutique', '68371276', '@naluboutique_altamira', '93991348740'],

            // ── Materiais de Construção ──
            ['Materiais de Construção', 'Armazém da Construção', '68372780', '@armazemdacontrucaoaltamira', '9335153635'],
            ['Materiais de Construção', 'Casa Covre', '68374272', '@casacovre', '93992322000'],
            ['Materiais de Construção', 'RDN', '68371275', '@rdnlocacoeservivosltda', '93991010210'],
            ['Materiais de Construção', 'Bella Casa', '68375080', '@bella.casaaltamira', '93991270801'],
            ['Materiais de Construção', 'Mix da Construção', '68375410', '@mixdaconstrucaoatm', '93991932899'],
            ['Materiais de Construção', 'Elias Móveis', '68372222', '@eliasmoveisatm_', '93992370619'],
            ['Materiais de Construção', 'Zé da Construção', '68376600', '@zeconstrucao', '93991696022'],
            ['Materiais de Construção', 'Primavera Construção', '68372833', '@primaveracontrucao', '93991764243'],

            // ── Oficina de Motos ──
            ['Oficina de Motos', 'Bita Motos', '68377395', '@biramotos_altamira', '93991442360'],
            ['Oficina de Motos', 'Kiko Moto Peças', '68372574', '@kikomotopecas.atm', '93991371909'],
            ['Oficina de Motos', 'Oliveira Motos', '68371440', '@oliveira.motos', '9335150261'],
            ['Oficina de Motos', 'Minas Moto Peças', '68372574', '@minasmotopecasatm', '93991985533'],
            ['Oficina de Motos', 'Vip Moto Peças e Serviços', '68372191', '@vip_motos.oficina', '93992047327'],

            // ── Ótica ──
            ['Ótica', 'Ótica Moderna', '68371000', '@otica_moderna_atm', '93991061402'],
            ['Ótica', 'Ótica Juh de Paula', '68371057', '@oticajuhdepaula', '94992679347'],
            ['Ótica', 'Ótica Bahia', '68371000', '@otica.bahia', '93992186464'],
            ['Ótica', 'Óticas Marcely', '68371000', '@oticasmarcely', '93991101287'],
            ['Ótica', 'Ótica Progresso', '68371425', '@oticaprogrssoatm', '93991254873'],
            ['Ótica', 'Viver Óticas', '68371203', '@viveroticasoficial', '93991063703'],
            ['Ótica', 'Ótica Central', '68371000', '@oticascentralatm', '93988135918'],
            ['Ótica', 'Óticas Carol', '68371163', '@oticascarolaltamira', '93992335586'],
            ['Ótica', 'Óticas Sandra', '68370000', '@oticassandra', '93991220932'],
            ['Ótica', 'QÓculos', '68372005', '@qoculosatm', '93991372024'],
            ['Ótica', 'Óticas Italian', '68371000', '@oticasitalianatm', '93988042478'],

            // ── Perfumaria e Cosméticos ──
            ['Perfumaria e Cosméticos', 'Norte Cosméticos', '68371432', '@norte_cosmeticos', '93991399438'],
            ['Perfumaria e Cosméticos', 'Jay Perfumaria', '68372859', '@jayperfumariaatm', '93992309711'],
            ['Perfumaria e Cosméticos', 'Shop Beauty', '68371000', '@shopbeauty.br', '93992069280'],
            ['Perfumaria e Cosméticos', 'Casa da Beleza', '68371125', '@casadabelezaaltamira', '9335154350'],
            ['Perfumaria e Cosméticos', 'Miss Beleza', '68371125', '@missbelezaoficial', '93992032782'],

            // ── Pet Shop ──
            ['Pet Shop', 'Pet Glamour', '68371025', '@pet_glamour831', '93984198844'],
            ['Pet Shop', 'Shop Dog & Cat', '68372005', '@shopdogcatatm', '93991732900'],
            ['Pet Shop', 'Espaço Pet Mpk', '68377590', '@espacopet.mpk', '93992128189'],
            ['Pet Shop', 'Pet Shop Mundo Animal', '68371163', '@gupoanimalpetsh', '93991982321'],
            ['Pet Shop', 'Bicho Sadio', '68371005', '@bichosadio', '93992332000'],
            ['Pet Shop', 'Animal Kingdom', '68371385', '@animalkingdompet', '93991627110'],
            ['Pet Shop', 'Empório Pet', '68371163', '@emporio.atm', '93991096407'],
            ['Pet Shop', 'Mascote PetShop', '68371041', '@mascoteclinivet.altamira', '93991443339'],

            // ── Pizzaria ──
            ['Pizzaria', 'Tal da Pizza', '68371159', '@taldapizzaatm', '93991516060'],
            ['Pizzaria', 'Roma Pizzaria Artesanal Altamira', '68372570', '@romapizzariaartesanal_atm', '93991749546'],
            ['Pizzaria', 'Forno Du Cheff', '68372670', '@doncheffaltamira', '93991996368'],
            ['Pizzaria', 'Mister Burger', '68372085', '@misterburg', '93991518014'],
            ['Pizzaria', 'Marguerita', '68373300', '@margueritapizzaatm', '93992378307'],
            ['Pizzaria', 'Mamma Flor', '68372690', '@mammaflorpizzaria', '93991242534'],
            ['Pizzaria', "Caborna's Grill", '68371045', '@cabornasgrill', '93992022693'],
            ['Pizzaria', 'Pizza Wee', '68372050', '@pizza.wee', '93991311588'],
            ['Pizzaria', 'Don Cheff Altamira', '68372670', '@donchefealtamira', '93991996368'],
            ['Pizzaria', 'Froes Pizzaria', '68371040', '@pizzaria_froes', '93984435373'],

            // ── Posto de Combustível ──
            ['Posto de Combustível', 'Auto Posto Vitória', '68371286', '@postovtoriaatm', '9335153040'],
            ['Posto de Combustível', 'Auto Posto JF', '68375343', '@auto.postojf', ''],
            ['Posto de Combustível', 'Postos GDias', '68371020', '@postogdiasoficial', '9135151679'],
            ['Posto de Combustível', 'Posto Brigadeiro', '68375000', '@postobrigadeiro', '93991382691'],
            ['Posto de Combustível', 'Posto Bonanza', '68370000', '@postobonanza', '93992396932'],
            ['Posto de Combustível', 'Xingu Auto Posto', '68371275', '@xinguautoposto', ''],
            ['Posto de Combustível', 'Posto Nápoles', '68371163', '@postonapoles', '93992402244'],
            ['Posto de Combustível', 'Posto Bakana', '68375460', '@postobakana', ''],

            // ── Provedor de Internet ──
            ['Provedor de Internet', 'Interlig', '68372574', '@interlig', '8004045005'],
            ['Provedor de Internet', 'MOV Fibra', '68371159', '@queromov', '80007210554'],
            ['Provedor de Internet', 'Xingu Telecom', '68372573', '@xtfibra', '8000420220'],
            ['Provedor de Internet', 'Você Telecom', '68372095', '@vctelecom', '8002803223'],
            ['Provedor de Internet', 'Proserv', '68372005', '@proserv_provedor', '93981161692'],
            ['Provedor de Internet', 'AutoServiço.com', '68376570', '@autoservicotelecom', '9335155184'],
            ['Provedor de Internet', 'WSP Fibra', '68372005', '@wspfibra', '9321000110'],
            ['Provedor de Internet', 'Liberty Pro', '68375399', '@libertypro_', '93991921704'],
            ['Provedor de Internet', 'Radius Telecom', '68377595', '@radius.atm', '93991859681'],

            // ── Restaurante ──
            ['Restaurante', 'Peixaria Ver o Rio', '68371040', '@veroriorestaurante', '93992315770'],
            ['Restaurante', 'Empório Cosmopolita', '68372040', '@emporiocosmopolita', '93991507242'],
            ['Restaurante', 'Deck Bar e Restaurante', '68371530', '@deckatm', '93991420802'],
            ['Restaurante', 'Royale Bar e Restaurante', '68372020', '@royale_atm', '93992373059'],
            ['Restaurante', 'Memórias do Xingu', '68371040', '@memoriasdoxingu', '93991501163'],
            ['Restaurante', 'Palaffitas', '68372040', '@palaffitasatm', '93991501163'],
            ['Restaurante', 'Restaurante do Gomes', '68371385', '@restaurantedogomes', '93996552242'],
            ['Restaurante', 'O Sabor de Casa', '68372880', '@sabordecasa_sc', '93991693529'],
            ['Restaurante', 'Point do Espetinho', '68371105', '@poiintdoespetinhoo', '93981163122'],
            ['Restaurante', 'Casa do Tambaqui', '68376804', '@acasadotambaqui', '93992364444'],

            // ── Salão de Beleza ──
            ['Salão de Beleza', 'Victoria Soulh Hair', '68375070', '@victoriasoulhair', '93991005912'],
            ['Salão de Beleza', 'Espaço Orys', '68375550', '@espacoorys', '93991519130'],
            ['Salão de Beleza', 'Cultura Cacheada SPA Afroamazônico', '68372180', '@culturacacheada_spa', '91991534123'],
            ['Salão de Beleza', 'Keity Fashion', '68372690', '@salao_keity_fashion', '93991306282'],
            ['Salão de Beleza', 'Mendes Cabeleireiro', '68371456', '@mendescabeleireiro_', '9335152205'],
            ['Salão de Beleza', 'Studio Safira', '68371000', '@studiossafira', '93991613951'],
            ['Salão de Beleza', 'Beauty Concept', '68372650', '@beautyconceptsalonn', '93991887878'],
            ['Salão de Beleza', 'Tok de Pele', '68372005', '@salaotokdepele', '9335157699'],
            ['Salão de Beleza', 'Kley Cabeleireira', '68371025', '@kleypassarelli', '93991446188'],
            ['Salão de Beleza', 'Salão e Estética Luiza Bertolo', '68371286', '@luizabertolo', '93991276999'],

            // ── Segurança Privada ──
            ['Segurança Privada', 'VB Segurança Inteligente', '68371163', '@vbseguranca.monitoramento', '93992210301'],
            ['Segurança Privada', 'Ad Sumus Sistemas de Segurança', '68040606', '@adsumus24h', '93992144422'],

            // ── Sorveteria ──
            ['Sorveteria', 'Xingu Açaí', '68370005', '@xinguacaii', '93991949409'],
            ['Sorveteria', 'Chiquinho Sorvetes', '68371163', '@chiquinhoaltamira01', ''],
            ['Sorveteria', 'Sorveteria Coelho', '68371286', '@sorveteriacoelho_atm', '93999083939'],
            ['Sorveteria', 'Tocari Sorvetes', '68371105', '@tocarissorvetesatm', '9335154102'],
            ['Sorveteria', 'Mr. Mix Milk Sheik', '68371000', '@mrmixmilkshakes.altamira', '93991826374'],
            ['Sorveteria', 'Frutos de Goiás', '68372005', '@frutosgoias.altamira', '93981101780'],
            ['Sorveteria', 'Kaingó', '68372222', '@kaingo.altamira', ''],
            ['Sorveteria', 'Expresso Goiano', '68371163', '@expressogoiano', '93991537971'],
            ['Sorveteria', 'Fischer Moments', '68371040', '@fidcher_sorvetes', '93992023067'],

            // ── Supermercado ──
            ['Supermercado', 'Primavera Supermercados', '68371000', '@primaverasupermercados', '93992299929'],
            ['Supermercado', 'Milênio Supermercado', '68372820', '@mileniosupermecado', '93991561467'],
            ['Supermercado', 'Milênio Express', '68372040', '@milênioexpress', '93992095720'],
            ['Supermercado', 'Peg & Pag Avenida', '', '', ''],
            ['Supermercado', 'Mix Mateus', '68378329', '@mixmateusoficial', ''],
            ['Supermercado', 'Nossa Horta', '68372855', '@nossahortasupermercado', '93984096401'],
            ['Supermercado', 'Supermercado Castro Mix', '68377270', '@supermercadocastromix', '9335930494'],
            ['Supermercado', 'Campeiro Supermercado', '68372586', '@campeirosupermercados', '9335151464'],

            // ── Táxi e Aplicativo de Transporte ──
            ['Táxi e Aplicativo de Transporte', 'Urbano Norte', '68372618', '@urbanonortealtamira', '93991011157'],
            ['Táxi e Aplicativo de Transporte', 'EasyMob', '68370000', '@easymob_oficial', '93991493136'],
            ['Táxi e Aplicativo de Transporte', 'Buscaê Altamira', '68373113', '@buscaebrasil', '93991427465'],
            ['Táxi e Aplicativo de Transporte', 'Zarppy Mob', '', '@zarppymob', '93991647232'],
            ['Táxi e Aplicativo de Transporte', 'Xingu Mob', '', '@xingumob', '93988149874'],

            // ── Vidraçaria ──
            ['Vidraçaria', 'Ideal vidraçaria', '68371057', '@idealvidracariaatacadoevarejo', '93991622163'],
            ['Vidraçaria', 'WG Vidros', '68371286', '@wgvidrosatm', '93991607648'],
            ['Vidraçaria', 'Casa dos Quadros', '68371085', '@casadosquadrosatm', '93991062023'],
            ['Vidraçaria', 'Visplan', '63837100', '@visplan_', '93991003130'],
            ['Vidraçaria', 'Globo Vidraçaria', '68371163', '@globovidracaria', '93991084346'],
        ];

        // Cache de categorias por slug
        $categoryCache = [];
        $companyCount = 0;
        $linkCount = 0;
        $cnpjCounter = Company::count();

        foreach ($empresas as [$categoryName, $companyName, $cep, $instagram, $whatsapp]) {
            $companyName = trim($companyName);
            $categoryName = trim($categoryName);

            if (empty($companyName) || empty($categoryName)) {
                continue;
            }

            // Buscar ou cachear a categoria
            $catSlug = Str::slug($categoryName);
            if (!isset($categoryCache[$catSlug])) {
                $categoryCache[$catSlug] = Category::where('slug', $catSlug)->first();
            }
            $category = $categoryCache[$catSlug];

            if (!$category) {
                $this->command->warn("   ⚠️  Categoria não encontrada: '{$categoryName}'");
                continue;
            }

            // Criar ou buscar a empresa pelo slug
            $slug = Str::slug($companyName);
            $company = Company::where('slug', $slug)->first();

            if (!$company) {
                $cnpjCounter++;
                $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsapp ?? '');
                $cleanCep = preg_replace('/[^0-9]/', '', $cep ?? '');

                // CEP válido = 8 dígitos
                if (strlen($cleanCep) !== 8) {
                    $cleanCep = null;
                }

                $company = Company::create([
                    'legal_name'        => $companyName,
                    'slug'              => $slug,
                    'email'             => $slug . '@empresa.temp',
                    'cnpj'              => sprintf('00000000%06d', $cnpjCounter),
                    'responsible_name'  => $companyName,
                    'responsible_phone' => $cleanWhatsapp ?: '0000000000',
                    'whatsapp_number'   => $cleanWhatsapp ?: null,
                    'address_zipcode'   => $cleanCep,
                ]);

                $companyCount++;
            }

            // Vincular empresa à categoria (sem duplicar)
            if (!$company->categories()->where('category_id', $category->id)->exists()) {
                $company->categories()->attach($category->id);
                $linkCount++;
            }
        }

        $this->command->info("   ✅ {$companyCount} empresas criadas.");
        $this->command->info("   ✅ {$linkCount} vínculos categoria↔empresa criados.");
    }
}
