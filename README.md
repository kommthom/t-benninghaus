<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://blob.t-benninghaus.de/docfunc-dark-badge.png" width="30%">
    <img alt="Badge changing depending on mode." src="https://blob.t-benninghaus/docfunc-light-badge.png" width="30%">
  </picture>
</p>

<p align="center">
  <img src="https://github.com/kommthom/t-benninghaus/actions/workflows/tests.yaml/badge.svg" alt="Tests">
  <a href="https://codecov.io/gh/kommthom/t-benninghaus" >
    <img src="https://codecov.io/gh/kommthom/t-benninghaus/graph/badge.svg?token=K2V2ANX2LW" alt="Codecov"/>
  </a>
</p>

## Einführung

t-benninghaus ist ein persönlicher Blog, der auf Laravel 13, Livewire 4 und Tailwind CSS 4 basiert, als Laravel-Lernspielplatz verwendet wird und von CKEditor 5 für die Erstellung, Algolia für die Volltextsuche und AWS S3 für Bild-Uploads unterstützt wird.

## Features

- **Posts** — CKEditor-5-Authoring mit Shiki-Syntaxhervorhebung, S3-Bild-Uploads, Soft-Deletes mit Massenlöschung, RSS-Feed via `spatie/laravel-feed`, und Algolia Volltextsuche.
- **Comments** — Hierarchische Threads mit E-Mail- und Datenbankbenachrichtigungen über eine Warteschlange.
- **Authentication** — Anmeldung, Registrierung, E-Mail-Verifizierung und Kontolöschung per signierter E-Mail.
- **WebAuthn passkeys** — Registrieren, verwalten und anmelden mit Passkeys.
- **Tags & Categories** — Tag-Eingabe mittels Tagify mit Many-to-Many-Tags und beitragsbezogenen Kategorien.
- **oEmbed** — Twitter und YouTube oEmbed proxy endpoints.
- **Bot protection** — Cloudflare Turnstile auf öffentlichen Formularen.
- **Settings** — Zugriff auf Anwendungseinstellungen über ein `Setting`-Modell und einen Service.

## Tech stack

### Backend

- PHP `^8.4`
- Laravel 13
- Laravel Octane 2
- Laravel Sanctum 4
- Laravel Scout 10

### Frontend

- Livewire 4 mit `livewire/blaze`
- Tailwind CSS 4 (via `@tailwindcss/vite`) und `@tailwindcss/typography`
- Vite 8
- TypeScript 5
- CKEditor 5
- Shiki
- Tagify (`@yaireo/tagify`)
- `@simplewebauthn/browser`

### Integrationen

- Algolia
- AWS S3
- Cloudflare Turnstile
- Bref (AWS Lambda)

## Vorraussetzungen

- PHP `^8.4`
- Composer
- Node.js und npm

> [!NOTIZ]
> SQLite ist die Standarddatenbank. Jede von Laravel unterstützte Datenbank (MySQL, PostgreSQL usw.) funktioniert mit ein paar Anpassungen der Umgebungsvariablen.

## Installation

Klone das Repository.:

```sh
git clone https://github.com/kommthom/t-benninghaus.git
cd docfunc
```

Installiere PHP Abhängigkeiten:

```sh
composer install
```

Kopiere die example env Datei:

```sh
cp .env.example .env
```

Generiere den Anwendungsschlüssel:

```sh
php artisan key:generate
```

Erstelle die SQLite-Datenbankdatei (überspringe diesen Schritt, wenn Du nicht den Standard-SQLite-Treiber verwendest):

```sh
touch database/database.sqlite
```

Führe die Migrationen aus:

```sh
php artisan migrate
```

Installiere JavaScript Abhängigkeiten:

```sh
npm install
```

Starte den Vite-Entwicklungsserver (oder `npm run build` für ein Produktions-Bundle).:

```sh
npm run dev
```

Starte den Laravel dev server:

```sh
php artisan serve
```

> [!NOTIZ]
> `composer create-project` würde das Kopieren der Umgebungsvariablen, die Schlüsselgenerierung und die Migrationen automatisch über Composer-Skripte ausführen. Ein einfaches `git clone` tut dies nicht; deshalb sind diese Schritte explizit aufgeführt.

## Konfiguration

- **Database** — Standard ist SQLite („database/database.sqlite“). Wechsel über „DB_CONNECTION“ und die Standardvariablen „DB_*“ in „.env“.
- **Mail** — Standardmäßig auf `MAIL_MAILER=log` gesetzt. Für den tatsächliche Versand die SMTP-Variablen konfigurieren.
- **Filesystem & S3** — Standardmäßig ist `FILESYSTEM_DISK=local` eingestellt. Für Bild-Uploads im CKEditor wird S3 verwendet; konfiguriere dazu `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION` und `AWS_BUCKET`.
- **Algolia / Scout** — Lege `ALGOLIA_APP_ID` ​​und `ALGOLIA_SECRET` fest. `SCOUT_PREFIX` weist Indizes je nach Umgebung einen Namensraum zu.
- **Cloudflare Turnstile** — Lege „CAPTCHA_SITE_KEY“ und „CAPTCHA_SECRET_KEY“ fest. Standardwerte in „.env.example“ sind die „always pass" -Testschlüssel von Cloudflare.
- **Octane** — `OCTANE_SERVER` akzeptiert `swoole`, `roadrunner`, oder `frankenphp`. Standard in `.env.example` ist `swoole`.

## Entwicklung

- `php artisan serve` — dev server.
- `npm run dev` — Vite dev server mit HMR.
- `php artisan octane:start` — production-style server (uses `OCTANE_SERVER`).
- `php artisan pail` — Anwendungsprotokolle in Echtzeit verfolgen
- `php artisan ide-helper:generate` und `php artisan ide-helper:models` — Metadaten für die IDE-Autovervollständigung generieren.

## Testen & Code Qualität

Das Projekt verwendet Pest 4 (mit `pest-plugin-browser` auf Basis von Playwright), Larastan/PHPStan auf Level 5 sowie Laravel Pint. Das CI-Skript führt alle drei aus:

```sh
composer ci
```

Oder führen Sie sie einzeln aus:

```sh
php artisan test --parallel    # or: vendor/bin/pest --parallel
vendor/bin/phpstan analyse --memory-limit=2G
vendor/bin/pint --parallel
```

> [!NOTIZ]
> Die Tests werden gegen eine In-Memory-SQLite-Datenbank mit dem `collection`-Treiber von Scout ausgeführt (konfiguriert in der `phpunit.xml`).

## Deployment

### Octane

t-benninghaus läuft auf [Laravel Octane](https://laravel.com/docs/octane), das drei Anwendungsserver unterstützt: Swoole, RoadRunner und FrankenPHP. Wähle einen davon über `OCTANE_SERVER` aus und starte ihn mit:

```sh
php artisan octane:start --server=$OCTANE_SERVER --host=0.0.0.0 --port=8000
```

> [!NOTE]
> Installieren Sie Swoole über PECL (`pecl install swoole`) oder apt (`sudo add-apt-repository ppa:ondrej/php` und anschließend `sudo apt-get install php8.4-swoole`). Informationen zu den anderen Servern finden Sie unter <https://roadrunner.dev> und <https://frankenphp.dev>.

### Supervisor

Octane worker (`/etc/supervisor/conf.d/docfunc-octane-worker.conf`):

```text
[program:docfunc-octane-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/docfunc/artisan octane:start --server=<swoole|roadrunner|frankenphp> --host=0.0.0.0 --port=8000
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/supervisor/docfunc-octane-worker.log
stopwaitsecs=3600
```

Queue worker (`/etc/supervisor/conf.d/docfunc-queue-worker.conf`):

```text
[program:docfunc-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/docfunc/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/supervisor/docfunc-queue-worker.log
stopwaitsecs=3600
```

### Scheduler

Edititier die crontab:

```sh
crontab -e
```

Fügen Sie den folgenden Eintrag hinzu, um den Laravel-Planer (https://laravel.com/docs/scheduling) jede Minute auszuführen:

```text
* * * * * cd /var/www/docfunc && php artisan schedule:run >> /dev/null 2>&1
```

### Serverless (Bref / AWS Lambda)

Das Projekt bündelt `bref/bref` und `bref/laravel-bridge` für AWS Lambda. Die Produktion läuft als Lambda-Funktion `docfunc-production-web` in `us-west-2`. Informationen zur Einrichtung finden Sie unter <https://bref.sh>.

## CI

- **Tests** (`.github/workflows/tests.yaml`) — Läuft bei Pull-Requests und Pushes auf `main` mit einer Matrix für PHP 8.4 und 8.5; führt PHPStan, Pint (`--test`) und Pest aus (mit Upload der Code-Abdeckung an Codecov) und sendet Statusmeldungen an Telegram.
- **Maintenance toggle** (`.github/workflows/maintenance-mode-toggle.yaml`) — Jährlicher Versand; schaltet den „MAINTENANCE MODE“ (Wartungsmodus) der Lambda-Funktion `docfunc-production-web` ein.