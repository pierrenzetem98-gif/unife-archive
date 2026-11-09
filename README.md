# Archivio Esami - UniFe Informatica

Piattaforma collaborativa dedicata agli studenti del Corso di Laurea in Informatica dell'Università degli Studi di Ferrara (UniFe). Permette di consultare, scaricare e condividere tracce e prove d'esame in formato PDF.

---

## Funzionalità principali

- **Organizzazione didattica**: materie classificate per anno di corso (I, II, III Anno).
- **Download istantaneo**: scaricamento diretto dei PDF, oppure archivi ZIP per materia, per anno o dell'intero archivio.
- **Upload collaborativo**: gli studenti approvati possono caricare nuove tracce indicando anno accademico e tipologia d'esame. I file caricati finiscono nella sezione *Community* finché l'admin non li ufficializza.
- **Accesso verificato**: ogni nuovo account resta in attesa finché non viene attivato (vedi sotto).
- **Gestione ruoli**:
  - `admin`: approvazione degli utenti, generazione dei codici di attivazione, ufficializzazione ed eliminazione dei file.
  - `student`: consultazione, download e upload delle tracce.

---

## Attivazione dei nuovi account

1. Lo studente si registra e viene reindirizzato alla pagina **"Attiva il tuo account"**.
2. Scrive all'admin su Instagram (**@pierre.nzetem**) indicando nome ed email.
3. L'admin, da `/admin/approvazioni`, clicca **"Genera codice"**: il codice (es. `K7M-4QX`) viene mostrato **una sola volta**, insieme a un messaggio pronto da copiare.
4. Lo studente inserisce il codice nella pagina di attesa e il suo account viene attivato subito.

Sicurezza del codice:

| Regola | Valore |
| :--- | :--- |
| Memorizzazione | hash (come una password), mai leggibile in chiaro |
| Validità | 48 ore |
| Tentativi errati | blocco dopo 5 tentativi |
| Limite richieste | 10 tentativi al minuto |
| Validità per account | un codice vale solo per l'account per cui è stato generato |

L'admin può sempre approvare direttamente con il pulsante **"Approva"**, oppure generare un nuovo codice (che annulla il precedente).

---

## Stack tecnologico

| Livello | Tecnologia | Ruolo |
| :--- | :--- | :--- |
| Backend | **Laravel** (PHP 8.2+) | Routing, controller, Eloquent ORM, middleware, comandi Artisan |
| Frontend | **Blade** | Rendering lato server |
| Stile | **Tailwind CSS 3.x** | Palette blu notte (`#0f172a` / `#1e3a8a`), bianco e oro (`#d97706` / `#f59e0b`) |
| Interattività | **Alpine.js** | Tab, copia negli appunti, piccole interazioni |
| Database | **MySQL** (produzione) / **SQLite** (locale e test) | `users`, `subjects`, `exams`, `archives` |
| File | **Laravel Storage** + `ZipArchive` | PDF in `storage/app/public`, generazione degli ZIP |
| Autenticazione | **Laravel Breeze** + middleware `approved` e `admin` | Sessioni, registrazione, ruoli, attivazione |
| Build | **Vite** | Compilazione CSS/JS |

---

## Installazione locale

Requisiti: PHP 8.2+, Composer, Node.js 18+.

```bash
git clone <URL_REPOSITORY>
cd unife-exams

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Nel file `.env` usa SQLite in locale:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
```

Poi:

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan app:create-admin tua-email@example.com
```

Avvio (due terminali):

```bash
php artisan serve
npm run dev
```

Il sito è disponibile su http://127.0.0.1:8000.

> ⚠️ Non usare mai in locale il `.env` di produzione: i comandi Artisan agirebbero sul database online.

---

## Comandi Artisan

| Comando | Descrizione |
| :--- | :--- |
| `php artisan app:create-admin {email}` | Crea un account admin o promuove/aggiorna quello esistente (password richiesta in modo nascosto) |
| `php artisan exams:import --dry-run` | Mostra quali PDF verrebbero importati da `import_exams/` senza scrivere nulla |
| `php artisan exams:import --year=2024/2025` | Importa i PDF di `import_exams/` (sottocartelle incluse), senza duplicati |

Struttura attesa per l'import: una cartella per materia, ad esempio `import_exams/I - Fisica/esame/compito.pdf`. Il prefisso dell'anno (`I - `, `II - `, `III - `) viene ignorato.

---

## Test di sicurezza

Lo script di audit esegue i percorsi principali e verifica i permessi su un database SQLite temporaneo (il database MySQL non viene mai toccato):

```bash
tests/audit/run.sh
```

Verifica, tra le altre cose, che:
- un ospite venga reindirizzato al login;
- uno studente non possa accedere all'area admin né approvare altri account;
- un account in attesa venga reindirizzato alla pagina di attivazione;
- non esista alcuna route pubblica in grado di modificare il database.

---

## Deploy (InfinityFree)

L'hosting non offre un terminale, quindi:

1. **Assets**: `rm -f public/hot && npm run build`, poi carica `public/build/` via FTP.
2. **Codice**: carica via FTP i file modificati negli stessi percorsi.
3. **Database**: applica le nuove migrazioni tramite **phpMyAdmin** (SQL equivalente) e registra la migrazione nella tabella `migrations`.
4. **Cache**: elimina sul server `bootstrap/cache/config.php` e `bootstrap/cache/routes-v7.php` se presenti.
5. Il `.env` di produzione deve avere `APP_ENV=production` e `APP_DEBUG=false`, e non va mai versionato.

---

## Struttura del progetto

```text
unife-exams/
├── app/
│   ├── Console/Commands/
│   │   ├── CreateAdmin.php                  # app:create-admin
│   │   └── ImportInitialExams.php           # exams:import
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── UserApprovalController.php   # Approvazioni, codici di attivazione, ufficializzazione
│   │   │   ├── Auth/
│   │   │   │   ├── ActivationController.php     # Verifica del codice inserito dallo studente
│   │   │   │   └── ...                          # Controller Breeze
│   │   │   ├── ExamController.php           # Upload, download, ZIP
│   │   │   ├── ProfileController.php
│   │   │   └── SubjectController.php
│   │   └── Middleware/
│   │       ├── EnsureUserIsApproved.php     # alias "approved"
│   │       └── EnsureUserIsAdmin.php        # alias "admin"
│   └── Models/
│       ├── Archive.php
│       ├── Exam.php
│       ├── Subject.php
│       └── User.php                         # Ruoli + logica del codice di attivazione
│
├── bootstrap/app.php                        # Registrazione degli alias middleware
│
├── database/
│   ├── migrations/                          # users, subjects, exams, archives, approvazione, codice di attivazione
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── SubjectSeeder.php                # Materie ufficiali UniFe
│
├── resources/views/
│   ├── admin/approvals.blade.php            # Dashboard admin (utenti + file community)
│   ├── auth/approval-pending.blade.php      # Pagina di attesa con inserimento del codice
│   ├── components/                          # navbar, footer
│   ├── layouts/                             # app, guest
│   ├── subjects/                            # index (elenco materie), show (dettaglio materia)
│   └── profile/
│
├── routes/
│   ├── auth.php                             # Breeze
│   └── web.php                              # Materie, esami, ZIP, profilo, attivazione, area admin
│
├── storage/app/public/
│   ├── archives/                            # ZIP generati
│   └── exams/                               # PDF
│
├── tests/audit/
│   ├── audit.php                            # Scenari HTTP di sicurezza
│   └── run.sh                               # Esecuzione su SQLite temporaneo
│
├── import_exams/                            # PDF sorgente da importare (non versionato)
├── .env.example
├── composer.json
├── package.json
├── tailwind.config.js
└── vite.config.js
```

---

## Autore

Progetto realizzato da **Pierre Nzetem**, studente di Informatica all'Università di Ferrara. Instagram: [@pierre.nzetem](https://instagram.com/pierre.nzetem)
