You are building a SIMPLE voting platform in Laravel with:
- Filament Admin (backoffice)
- Public user-facing screens in Blade (voter registration + voting)
Keep it pragmatic and shippable. Avoid overengineering.

## Configuração das edições

No painel administrativo, use **Conteúdo → Configurações da Edição** para alterar
o ano, a logomarca, a data/hora de encerramento, o PDF do regulamento, o cadastro
público de empresas e o embaralhamento das opções de votação.
O acesso reutiliza a permissão `Update:LandingPageSection`.

- O ano é uma edição: mudar a edição permite um novo voto por participante e
  categoria, sem apagar votos anteriores.
- O encerramento global bloqueia a votação no servidor; os prazos individuais
  das categorias também continuam valendo. As datas usam o fuso de `config/app.php`.
- Em **Seções da Landing**, use `{ano}` nos títulos e conteúdos para acompanhar
  automaticamente o ano configurado. Textos legados com `2025` também são
  atualizados na exibição.
- Cadastros de empresas estão em `/empresa/cadastro`: nome fantasia, CNPJ,
  WhatsApp, logomarca, categorias e Instagram opcional. Dados antigos são preservados.
  As categorias são selecionadas por caixas de marcação, inclusive no celular.
  Empresas antigas sem nome fantasia exibem a razão social também na busca,
  ordenação e edição administrativa.
- As categorias possuem links compartilháveis `/votacao/{slug}`, disponíveis
  para copiar na listagem administrativa. Links antigos `/vote/{slug}` continuam
  funcionando. O link de acesso por e-mail preserva a categoria compartilhada.
- A página inicial exibe a landing; resultados e vencedores não são publicados.
- Relatórios, listagens de votos, resultados, empresas, participantes, sorteios,
  dashboard e BIs têm seleção de ano e exportação `.xlsx`. O padrão é a edição
  mais recente entre as edições com votos e a edição configurada.
  A exportação do BI com empresas exige login administrativo.
- O ranking por categoria usa os componentes visuais do Filament nos modos claro
  e escuro, mantém a tabela rolável no celular e pede confirmação antes de definir
  uma empresa vencedora.
- Contagens de votos são filtradas por edição. Empresas e categorias não têm
  cadastro anual separado: suas informações cadastrais continuam compartilhadas.
- Nos prêmios de níveis 1 e 2, o mínimo de votos é configurado individualmente;
  mudar o nível não sobrescreve esse valor. No nível 3, é necessário votar em
  todas as categorias ativas, inclusive após o encerramento. Categorias inativas
  não contam para esse requisito. Homepage e indicadores acompanham esses valores.
  Prêmios desativados, esgotados ou de outra edição não são anunciados.
  O indicador público mostra o requisito de votos atingido; o sorteio semanal
  mantém seu recorte de votos desde o último sorteio (ou últimos sete dias),
  conforme o regulamento.

Na implantação, faça backup do banco, aplique as migrations com
`php artisan migrate --force` e configure a edição no painel antes de divulgar
a votação. A coluna `year` identifica a edição, independentemente da data de
cadastro: a migração separa todos os participantes, votos, prêmios e vencedores de categoria
existentes na edição **2025**, preservando suas datas originais. A edição nova
inicia em **2026** e as próximas são configuradas no painel. O rollback é bloqueado se
votos/vencedores de várias edições não couberem nas antigas restrições únicas.
Envie a nova logomarca no painel; os arquivos antigos não são renomeados nem
editados automaticamente.

Os testes utilizam SQLite em memória por padrão. O teste de dados dos BIs também
deve ser executado com `DB_CONNECTION=mysql` em banco MariaDB **exclusivo para
testes**, pois esses painéis usam funções SQL específicas desse banco.
As suítes `AdminInteractionsTest` e `PublicInteractionsTest` validam os cadastros,
edições, exclusões, ativação de prêmios e TVs, uploads, login por e-mail e saída
do participante sem encerrar a sessão administrativa. Não execute esses testes
contra um banco com dados reais.

TECH BASE
- Laravel (assume a fresh app). Use Filament v3 for admin.
- Blade for public UI (no SPA).
- Use standard Laravel features: migrations, Eloquent, Form Requests, Policies, rate limiting.
- Store uploads in local/public disk (companies logo, awards image).
- Code style: clean, small classes, no inline comments, English naming everywhere (db columns, classes, messages).

CORE RULES
- Audience (voter) registers with: name, email, birth_date, phone.
- After registering, they can vote in available categories.
- Each audience can vote ONLY ONCE per category and edition (enforced at DB unique index).
- Companies participate in one or many categories (many-to-many).
- Each category displays participating companies and lets audience vote for one company.
- Category voting screen must be shareable by companies on Instagram (a public URL that shows the category and the company list; if user is logged in it shows vote buttons; if not, it shows CTA to register/login).
- Admin manages: categories, companies, prizes (awards).
- Admin has a “live trophy winner” per category (manual selection in admin, but show vote counts to support it).
- Weekly draw: 1x per week draw winners among audience who voted, based on prizes list (awards). Record draw history.

AUTH FOR PUBLIC USERS (AUDIENCE)
- Do NOT create password flow.
- Implement “magic link” login via email:
  - After registration, email a signed URL (temporary) to authenticate.
  - Also allow requesting a new magic link via email.
- Keep session auth with a dedicated guard/provider for Audience OR a simple custom session auth (choose the cleanest minimal approach). Do not mix with Filament admin auth.

DATABASE DESIGN (MIGRATIONS + MODELS + RELATIONSHIPS)
1) audiences
- id
- year (registration edition; participants can return and vote in later editions)
- name (string)
- email (string unique)
- birth_date (date)
- phone (string)
- email_verified_at (nullable timestamp)
- created_at/updated_at

2) categories
- id
- name
- slug (unique)
- description (nullable text)
- is_active (bool default true)
- voting_starts_at (nullable timestamp)
- voting_ends_at (nullable timestamp)
- created_at/updated_at

3) companies
- id
- trade_name (nome fantasia; backfilled from legal_name for existing companies)
- legal_name (razao social) string
- whatsapp_number
- instagram_url (nullable)
- cnpj (string unique, validate format)
- responsible_name string
- responsible_phone string
- logo_path (nullable string)
- created_at/updated_at

4) category_company (pivot)
- category_id
- company_id
- timestamps
- unique(category_id, company_id)

5) votes
- id
- year (edition configured in admin)
- audience_id (fk)
- category_id (fk)
- company_id (fk)
- ip_hash (nullable string)
- user_agent (nullable string)
- created_at (timestamp)
Constraints:
- unique(audience_id, category_id, year)
- fk company_id must belong to the same category via pivot (validate in request)

6) awards (prizes)
- id
- year (edition)
- name
- description (nullable)
- image_path (nullable)
- quantity (int default 1)  // how many times it can be drawn
- is_active (bool default true)
- created_at/updated_at

7) award_draws
- id
- award_id (fk)
- audience_id (fk nullable until completed)
- status (enum: pending, completed, canceled) OR string
- drawn_at (nullable timestamp)
- meta (nullable json: store seed, filters used, etc)
- created_at/updated_at

8) category_winners
- id
- category_id (fk, unique together with year)
- year (edition)
- company_id (fk)
- chosen_at (timestamp)
- created_at/updated_at

BUSINESS LOGIC
- A category is “open” if:
  - is_active = true
  - (voting_starts_at is null OR now >= it)
  - (voting_ends_at is null OR now <= it)
- Voting:
  - Only for open categories.
  - Only for companies linked to that category.
  - One vote per audience per category (DB unique + validation).
- Weekly draw:
  - Admin triggers draw from Filament.
  - Pick an “available award”: is_active=1 and quantity_remaining > draws_completed.
  - Pick a random eligible audience:
    - Eligible = has at least one vote
    - For weekly logic: only votes created since last completed draw (or last 7 days) — implement “since last draw date, fallback last 7 days”.
  - Record award_draw (completed), decrement effective remaining by counting completed draws for that award (no separate decrement column needed, compute remaining).
  - Prevent drawing when no eligible audience or no remaining prizes.

FILAMENT ADMIN (BACKOFFICE)
- Resources:
  - CategoryResource (CRUD) with slug auto-generated.
  - CompanyResource (CRUD) with logo upload + multi-select categories.
  - AwardResource (CRUD) with image upload + quantity.
  - AudienceResource (read mostly; allow search/export).
  - VoteResource (read-only) with filters by category/company/date; show counts.
- Pages:
  1) “Category Results” page:
     - select category
     - show table of companies in that category with vote_count
     - action to set winner (writes category_winners)
     - show current winner if exists
  2) “Weekly Draw” page:
     - shows next available prize(s) and remaining counts
     - button “Run Draw”
     - shows last draws history (award, winner, date)
  Use Filament widgets if it’s simpler for the results summaries.

PUBLIC BLADE SCREENS (USER)
Routes:
- GET / -> list open categories + CTA
- GET /register -> registration form (name, email, birth_date, phone)
- POST /register -> create audience (or update if email exists but not verified), send magic link email
- GET /login -> request magic link form (email)
- POST /login -> send magic link
- GET /auth/magic/{signature} -> verify signed URL, log the audience in, redirect /vote
- POST /logout -> logout
- GET /vote -> list open categories (and show if already voted)
- GET /vote/{category:slug} -> category voting page:
   - list companies (logo + responsible name or legal name)
   - if already voted: show “You voted for X” and disable buttons
   - if not logged: show CTA to register/login
- POST /vote/{category:slug}/{company} -> cast vote

Shareability:
- The category page (/vote/{slug}) must be public and pretty.
- Add OpenGraph meta tags (title/description/image) so when a company shares, it previews well.
- Add a “Share” button that copies the URL.
- Optionally add query param ?ref=company_id to highlight a company card (visual emphasis only, must not auto-vote).

VALIDATION
- Use FormRequest for register, login, vote.
- Validate CNPJ format (simple regex + length, no need external lib).
- Phone normalize (keep digits).
- Prevent vote if audience already voted in that category.
- Rate limit:
  - register/login endpoints (e.g. 5/min per IP)
  - voting endpoint (e.g. 10/min per audience+IP)

EMAIL
- Create a Mailable for magic link.
- Signed URL valid for 30 minutes.
- Basic email template.

TESTS (MINIMUM)
- Feature test: audience can vote once per category (second vote blocked).
- Feature test: cannot vote for company not in category.
- Feature test: draw picks a voter only from eligible voters and records the draw.

DELIVERABLE
Generate:
- migrations
- models with relationships
- requests
- controllers
- mail + notification
- routes/web.php
- Blade views (simple, clean)
- Filament resources + custom pages
- policies/guards if needed
- tests

Start by creating the migrations and models, then routing/controllers, then Blade, then Filament, then tests.
Output code in full files with paths.
