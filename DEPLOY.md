# Deploy na Hostinger VPS

## Configuração do GitHub Actions

### Secrets necessários

Vá em **Settings > Secrets and variables > Actions** no seu repositório GitHub e adicione:

| Secret | Descrição | Exemplo |
|--------|-----------|---------|
| `HOSTINGER_HOST` | IP ou hostname da VPS | `123.456.789.0` |
| `HOSTINGER_USER` | Usuário SSH | `root` ou `usuario` |
| `HOSTINGER_SSH_KEY` | Chave SSH privada | Conteúdo do arquivo `~/.ssh/id_rsa` |
| `HOSTINGER_PORT` | Porta SSH | `22` (ou a porta configurada) |
| `APP_PATH` | Caminho da aplicação no servidor | `/var/www/xingu-votos` |

### Gerar chave SSH

No seu computador local:

```bash
# Gerar par de chaves
ssh-keygen -t ed25519 -C "github-actions-deploy"

# Exibir chave pública (adicionar no servidor)
cat ~/.ssh/id_ed25519.pub

# Exibir chave privada (adicionar nos secrets do GitHub)
cat ~/.ssh/id_ed25519
```

### Configurar servidor Hostinger

1. **Conectar via SSH:**
```bash
ssh root@SEU_IP_HOSTINGER
```

2. **Adicionar chave pública:**
```bash
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Cole a chave pública e salve

chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

3. **Instalar dependências:**
```bash
# Atualizar sistema
apt update && apt upgrade -y

# Instalar PHP 8.3
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl php8.3-imagick php8.3-exif

# Instalar MariaDB
apt install -y mariadb-server

# Instalar Nginx
apt install -y nginx

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Instalar Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Instalar Supervisor (para filas)
apt install -y supervisor
```

4. **Configurar MariaDB:**
```bash
mysql_secure_installation

mysql -u root -p
```

```sql
CREATE DATABASE xingu_votos;
CREATE USER 'xingu_votos'@'localhost' IDENTIFIED BY 'SUA_SENHA_SEGURA';
GRANT ALL PRIVILEGES ON xingu_votos.* TO 'xingu_votos'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

5. **Criar diretório da aplicação:**
```bash
mkdir -p /var/www/xingu-votos
chown -R www-data:www-data /var/www/xingu-votos
```

6. **Configurar Nginx** (`/etc/nginx/sites-available/xingu-votos`):
```nginx
server {
    listen 80;
    server_name seu-dominio.com.br;
    root /var/www/xingu-votos/public;
    index index.php index.html;
    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
ln -s /etc/nginx/sites-available/xingu-votos /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

7. **Configurar Supervisor** (`/etc/supervisor/conf.d/xingu-votos-worker.conf`):
```ini
[program:xingu-votos-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/xingu-votos/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/xingu-votos
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/xingu-votos/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
supervisorctl reread
supervisorctl update
supervisorctl start xingu-votos-worker:*
```

8. **Configurar Scheduler (Cron):**
```bash
crontab -e
```
Adicionar:
```
* * * * * cd /var/www/xingu-votos && php artisan schedule:run >> /dev/null 2>&1
```

9. **Configurar .env no servidor:**
```bash
nano /var/www/xingu-votos/.env
```

```env
APP_NAME="Xingu Votos"
APP_ENV=production
APP_KEY=base64:GERAR_NOVA_CHAVE
APP_DEBUG=false
APP_URL=https://seu-dominio.com.br

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=xingu_votos
DB_USERNAME=xingu_votos
DB_PASSWORD=SUA_SENHA_SEGURA

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

10. **Instalar SSL (Let's Encrypt):**
```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d seu-dominio.com.br
```

## Primeiro Deploy Manual

Antes do CI/CD funcionar, faça o primeiro deploy manual:

```bash
cd /var/www/xingu-votos

# Clone o repositório (primeira vez)
git clone https://github.com/SEU_USUARIO/xingu-votos-2.git .

# Instalar dependências
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Configurar
cp .env.example .env
nano .env  # Configurar variáveis

php artisan key:generate
php artisan migrate
php artisan storage:link

# Permissões
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## Testar Deploy

Após configurar tudo, faça um push para a branch `main` e acompanhe em **Actions** no GitHub.
