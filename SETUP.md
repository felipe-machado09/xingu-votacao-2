# Voting Platform - Setup Instructions

## Installation Steps

1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database**
   Edit `.env` and set your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=xingu_votos
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Configure mail**
   For magic link emails, configure mail in `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed database (optional)**
   ```bash
   php artisan db:seed
   ```
   This creates:
   - Admin user: admin@example.com / password
   - 5 categories
   - 20 companies
   - 10 awards
   - 50 test audiences

7. **Link storage**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the server**
   ```bash
   php artisan serve
   ```

## Access Points

- **Public Site**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
  - Email: admin@example.com
  - Password: password

## Running Tests

```bash
php artisan test
```

## Key Features Implemented

### Public Features
- Home page with open categories
- Registration with magic link authentication
- Login with magic link
- Vote in categories (once per category)
- Shareable category pages with OpenGraph meta tags
- Highlight company via `?ref=company_id` parameter

### Admin Features (Filament)
1. **Resources**
   - Categories (CRUD with auto slug)
   - Companies (CRUD with logo upload and category selection)
   - Awards (CRUD with image upload)
   - Audiences (read-only with search)
   - Votes (read-only with filters)

2. **Custom Pages**
   - Category Results: View vote counts and set winners
   - Weekly Draw: Run draws and view history

### Security
- Magic link authentication (30-minute expiration)
- Rate limiting on register/login (5/min)
- Rate limiting on voting (10/min)
- Unique constraint: one vote per audience per category
- CSRF protection
- Signed URLs for authentication

### Business Rules
- Categories have optional start/end dates
- Only votes in open categories
- Companies must be linked to category to receive votes
- Weekly draws pick from voters since last draw (or last 7 days)
- Award quantities tracked via completed draws
- Category winners manually selected by admin

## Database Schema

- `audiences` - Voters
- `categories` - Voting categories
- `companies` - Participating companies
- `category_company` - Many-to-many pivot
- `votes` - Vote records (unique per audience+category)
- `awards` - Prizes for weekly draws
- `award_draws` - Draw history
- `category_winners` - Trophy winners per category

## Development Notes

- All code in English (models, columns, messages)
- Clean architecture with Form Requests
- Eloquent relationships properly defined
- Filament v3 for admin panel
- Blade templates with Tailwind CSS
- PHPUnit tests for critical features
