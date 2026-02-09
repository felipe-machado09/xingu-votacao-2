# 🎁 Sistema de Prêmios Escalonados

## Visão Geral

O sistema de prêmios escalonados foi implementado para **incentivar mais participação** dos usuários na votação. Quanto mais categorias o usuário votar, mais prêmios ele pode ganhar!

## 📊 Níveis de Prêmios

### 🥉 Nível 1 - Prêmios Básicos
- **Requisito**: Vote em **5 empresas** (mínimo)
- **Prêmios**: Brindes, kits de produtos, vale-compras pequenos
- **Status**: Entrada no sorteio

### 🥈 Nível 2 - Prêmios Intermediários  
- **Requisito**: Vote em **15 empresas**
- **Prêmios**: Eletrodomésticos, cestas premium, vale-compras maiores
- **Status**: Concorre aos prêmios Nível 1 + Nível 2

### 🥇 Nível 3 - Prêmio Máximo
- **Requisito**: Vote em **TODAS as categorias disponíveis**
- **Prêmios**: TV, Notebook, vale-compras grandes
- **Status**: Concorre a TODOS os prêmios (Nível 1, 2 e 3)

## 🎯 Como Funciona para o Usuário

### Interface Visual
A página de votação mostra:
1. **Contador de votos**: Progresso do usuário (ex: "5 / 30 votos")
2. **Prêmios bloqueados**: Aparecem com opacidade reduzida e ícone de cadeado 🔒
3. **Prêmios desbloqueados**: Aparecem com destaque e anel verde ✓
4. **Mensagens de incentivo**: 
   - "Faltam 3 votos para desbloquear Nível 2!"
   - "Vote em mais 10 categorias para concorrer ao Prêmio Máximo!"

### Exemplo Prático

**João votou em 4 categorias:**
- ❌ Ainda não concorre (precisa de 5)
- Mensagem: "Faltam 1 voto para participar do sorteio"

**João votou em 5 categorias:**
- ✅ Desbloqueou Nível 1
- Concorre aos prêmios: Vale-compras R$ 100, Kit de Produtos, Brinde Exclusivo

**João votou em 15 categorias:**
- ✅ Desbloqueou Nível 1 e 2
- Concorre a TODOS os prêmios dos níveis 1 e 2

**João votou em TODAS as 30 categorias:**
- ✅ Desbloqueou TODOS os níveis
- Concorre ao prêmio máximo: TV, Notebook, Vale-compras R$ 1.000
- **MAIS** todos os outros prêmios dos níveis inferiores

## ⚙️ Configuração no Admin (Filament)

### Cadastrar Novo Prêmio

1. Acesse **Sorteios → Prêmios**
2. Clique em **Novo Prêmio**
3. Preencha:
   - **Nome**: Nome do prêmio
   - **Descrição**: Detalhes do prêmio
   - **Imagem**: Upload da foto (opcional)
   - **Nível do Prêmio**: 
     - `Nível 1 - Básico (5 votos)`
     - `Nível 2 - Intermediário (15 votos)`
     - `Nível 3 - Máximo (Todos os votos)`
   - **Mínimo de Votos**: Preenchido automaticamente baseado no nível
   - **Quantidade**: Quantas unidades disponíveis
   - **Ativo**: Marcar para aparecer no site

### Exemplo de Valores

```
Nível 1 (5 votos):
- min_votes = 5
- Prêmios simples, quantidade maior (10-20 unidades)

Nível 2 (15 votos):
- min_votes = 15
- Prêmios intermediários (5-10 unidades)

Nível 3 (Todos):
- min_votes = 999 (indica "todas as categorias")
- Prêmios premium (1-3 unidades)
```

## 🎲 Sorteio Semanal

### Como Funciona

O sorteio respeita os níveis:
1. Sistema busca o próximo prêmio disponível (ordena por tier)
2. Filtra participantes **elegíveis** para aquele prêmio:
   - **Nível 1**: Usuários com ≥ 5 votos
   - **Nível 2**: Usuários com ≥ 15 votos
   - **Nível 3**: Usuários que votaram em TODAS as categorias
3. Sorteia aleatoriamente entre os elegíveis
4. Registra o sorteio com metadados (tier, min_votes, elegíveis)

### Executar Sorteio

1. Acesse **Sorteios → Sorteio Semanal**
2. Clique em **Executar Sorteio**
3. Sistema automaticamente:
   - Seleciona próximo prêmio disponível
   - Filtra participantes elegíveis
   - Sorteia vencedor
   - Envia notificação

## 🗄️ Estrutura do Banco de Dados

### Tabela `awards`

```sql
- tier (integer): 1, 2 ou 3
- min_votes (integer): Mínimo de votos necessários
- quantity (integer): Quantidade disponível
- is_active (boolean): Se está ativo
```

### Exemplo de Query

```sql
-- Buscar prêmios Nível 2 ativos
SELECT * FROM awards 
WHERE tier = 2 
AND is_active = 1 
AND (quantity - completed_draws_count) > 0;

-- Contar votos de um usuário
SELECT COUNT(DISTINCT category_id) as total_votes
FROM votes
WHERE audience_id = 123;
```

## 📝 Seed de Exemplo

Para popular prêmios de teste:

```bash
php artisan db:seed --class=TieredAwardsSeeder
```

Isso criará:
- 3 prêmios Nível 1 (total 45 unidades)
- 3 prêmios Nível 2 (total 23 unidades)
- 3 prêmios Nível 3 (total 4 unidades)

## 🎨 Customização Visual

### Cores por Nível

- **Nível 1**: Azul (`bg-blue-100`, `text-blue-900`)
- **Nível 2**: Laranja (`bg-orange-100`, `text-orange-900`)
- **Nível 3**: Amarelo/Dourado (`bg-yellow-100`, `text-yellow-900`)

### Emojis

- Nível 1: 🥉 (bronze) + 🎁
- Nível 2: 🥈 (prata) + 🎁
- Nível 3: 🥇 (ouro) + 🏆

## 🚀 Benefícios do Sistema

1. **Gamificação**: Usuários são incentivados a votar mais
2. **Engajamento**: Aumento de participação em todas as categorias
3. **Transparência**: Usuários sabem exatamente o que podem ganhar
4. **Flexibilidade**: Admin pode ajustar níveis e prêmios facilmente
5. **Justiça**: Quem se dedica mais tem mais chances de ganhar prêmios melhores

## 📊 Métricas Sugeridas

Acompanhe no admin:
- Quantos usuários atingiram cada nível
- Taxa de conversão (5 votos → 15 votos → todos)
- Prêmios mais atrativos (por nível)
- Tempo médio para completar níveis

## 🔧 Manutenção

### Ajustar Requisitos

Para mudar os requisitos dos níveis, edite a migration ou ajuste via admin:

```php
// Exemplo: Mudar Nível 2 para 10 votos
$award->update(['min_votes' => 10]);
```

### Desativar Nível

Marque todos os prêmios de um nível como `is_active = false`

### Adicionar Novo Nível

1. Adicione novo valor em `tier` (ex: 4)
2. Atualize a lógica do WeeklyDraw se necessário
3. Adicione nova seção visual no Blade

## 📞 Suporte

Para dúvidas ou ajustes, consulte:
- `/app/Models/Award.php` - Modelo
- `/app/Filament/Resources/AwardResource.php` - Admin
- `/resources/views/vote/index.blade.php` - Interface
- `/app/Filament/Pages/WeeklyDraw.php` - Sorteio

---

**Sistema implementado em:** 02/02/2026  
**Versão:** 1.0  
**Status:** ✅ Produção
