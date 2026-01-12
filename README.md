You are building a SIMPLE voting platform in Laravel with:
- Filament Admin (backoffice)
- Public user-facing screens in Blade (voter registration + voting)
Keep it pragmatic and shippable. Avoid overengineering.

TECH BASE
- Laravel (assume a fresh app). Use Filament v3 for admin.
- Blade for public UI (no SPA).
- Use standard Laravel features: migrations, Eloquent, Form Requests, Policies, rate limiting.
- Store uploads in local/public disk (companies logo, awards image).
- Code style: clean, small classes, no inline comments, English naming everywhere (db columns, classes, messages).

CORE RULES
- Audience (voter) registers with: name, email, birth_date, phone.
- After registering, they can vote in available categories.
- Each audience can vote ONLY ONCE per category (enforced at DB unique index).
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
- legal_name (razao social) string
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
- audience_id (fk)
- category_id (fk)
- company_id (fk)
- ip_hash (nullable string)
- user_agent (nullable string)
- created_at (timestamp)
Constraints:
- unique(audience_id, category_id)
- fk company_id must belong to the same category via pivot (validate in request)

6) awards (prizes)
- id
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
- category_id (unique fk)
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
