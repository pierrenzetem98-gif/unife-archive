# Archivio Esami - UniFe Informatica

Piattaforma collaborativa open-source dedicata agli studenti del Corso di Laurea in Informatica dell'Università degli Studi di Ferrara (UniFe). Permette di consultare, scaricare e condividere tracce e prove d'esame in formato PDF.

---

## Funzionalità Principali

- **Organizzazione Didattica**: Materie classificate per Anno di corso (I Anno, II Anno, III Anno).
- **Download Istantaneo**: Scaricamento diretto dei compiti e delle prove parziali in formato PDF.
- **Upload Collaborativo**: Gli studenti autenticati possono caricare nuove tracce indicando anno accademico e tipologia d'esame.
- **Gestione Ruoli**:
  - `Admin`: Controllo completo ed eliminazione di qualsiasi file.
  - `Student`: Accesso, download e upload delle tracce.

---

## Installazione Locale

1. **Clonare il repository e accedere alla cartella**:
   ```bash
   git clone <URL_REPOSITORY>
   cd unife-exams


   ## 🛠️ Stack Technologique & Outils

| Domaine / Couche | Technologie / Outil | Description & Rôle |
| :--- | :--- | :--- |
| **Backend** | **Laravel 11** (PHP 8.2+) | Framework PHP moderne (Routage, Contrôleurs, Eloquent ORM, Seeders) |
| **Frontend** | **Blade Templates** | Moteur de templating natif pour le rendu côté serveur |
| **Styling & UI** | **Tailwind CSS 3.x** | Design responsive, palette *Bleu Nuit (#0f172a / #1e3a8a), Blanc et Or (#d97706 / #f59e0b)* |
| **Interactivité** | **Alpine.js** | Gestion dynamique des onglets et interactions client légères |
| **Base de Données** | **MySQL / SQLite** | Stockage persistant (`users`, `subjects`, `exams`, `archives`) |
| **Stockage & Fichiers** | **Laravel Storage & `ZipArchive` (PHP)** | Gestion du stockage local (`public/storage`) et streaming d'archives ZIP |
| **Authentification** | **Laravel Breeze** | Gestion complète des sessions, inscriptions et rôles (`Admin` / `Student`) |
| **Compilation d'Assets** | **Vite** | Bundler moderne pour CSS et JavaScript |
| **Contrôle de Version** | **Git & GitHub** | Gestionnaire de version et collaboration |

---

## 📁 Arborescence du Projet (`unife-exams`)

```text
unife-exams/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── ImportInitialExams.php       # Commande Artisan pour importer récursivement les dossiers de PDF
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                        # Contrôleurs d'authentification Laravel Breeze
│   │   │   ├── ExamController.php           # Upload, téléchargement individuel et génération des archives ZIP
│   │   │   ├── ProfileController.php        # Gestion du profil utilisateur
│   │   │   └── SubjectController.php        # Affichage des matières par année et filtres
│   │   └── Requests/                        # Règles de validation des formulaires
│   └── Models/
│       ├── Archive.php                      # Modèle de suivi des archives ZIP générées
│       ├── Exam.php                         # Modèle des épreuves PDF (titre, année, type, fichier, auteur)
│       ├── Subject.php                      # Modèle des matières d'enseignement (nom, année I/II/III)
│       └── User.php                         # Modèle utilisateur avec méthode isAdmin()
│
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php       # Table utilisateurs avec colonne 'role' (admin/student)
│   │   ├── ..._create_subjects_table.php    # Table des matières (I, II, III Anno)
│   │   ├── ..._create_exams_table.php       # Table des épreuves PDF rattachées aux matières
│   │   └── ..._create_archives_table.php    # Table d'historique des ZIP créés (nom, chemin, taille)
│   └── seeders/
│       ├── DatabaseSeeder.php               # Seeder central (comptes Admin et Étudiant par défaut)
│       └── SubjectSeeder.php                # Injection des matières officielles UniFe et sujets tests
│
├── public/
│   ├── build/                               # Assets compilés (CSS, JS) générés par Vite
│   └── storage -> ../storage/app/public     # Lien symbolique vers les fichiers PDF et ZIP
│
├── resources/
│   ├── css/
│   │   └── app.css                          # Directives Tailwind CSS
│   ├── js/
│   │   └── app.js                           # Script Alpine.js et bootstrap
│   └── views/
│       ├── components/
│       │   ├── navbar.blade.php             # Barre de navigation avec badge IG @pierre.nzetem & mode Admin
│       │   └── footer.blade.php             # Pied de page avec signature et mentions UniFe
│       ├── layouts/
│       │   ├── app.blade.php                # Layout principal adaptatif (Thème Admin sombre vs Étudiant clair)
│       │   └── guest.blade.php              # Layout des pages d'authentification
│       ├── subjects/
│       │   ├── index.blade.php              # Accueil : Téléchargement ZIP total, filtres par année & cartes matières
│       │   └── show.blade.php               # Page matière : liste des PDF, upload et téléchargement ZIP dédié
│       └── profile/                         # Vues de gestion du profil utilisateur
│
├── routes/
│   ├── auth.php                             # Routes Breeze (login, register, logout, password)
│   └── web.php                              # Routes de l'application (materie, esami, zip, profil)
│
├── storage/
│   └── app/
│       └── public/
│           ├── archives/                    # Stockage physique des fichiers ZIP générés
│           └── exams/                       # Stockage physique des épreuves PDF importées/téléversées
│
├── import_exams/                            # Dossier temporaire contenant l'arborescence des PDF sources à importer
├── .env.example                             # Exemple de configuration d'environnement
├── composer.json                            # Dépendances PHP (Laravel, ZipArchive, etc.)
├── package.json                             # Dépendances Node/NPM (Tailwind, Alpine, Vite)
├── tailwind.config.js                       # Configuration des couleurs et du thème Tailwind
├── vite.config.js                           # Configuration du bundler Vite
└── README.md                                # Documentation générale du projet



......