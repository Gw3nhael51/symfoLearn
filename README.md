# EventPulse

> Plateforme moderne de gestion d'événements, conférences et assistant d'intelligence artificielle locale conçue avec **Symfony 8.1**, **Tailwind CSS** et **Symfony AI**.

---

## 🚀 Fonctionnalités principales

- **Gestion des événements & conférences** : Consultation publique des conférences, affichage dynamique et filtrage d'envergure (nationale / internationale).
- **Espace d'administration sécurisé (EasyAdmin 5)** : Gestion complète du catalogue d'événements, réservée aux administrateurs authentifiés (`ROLE_ADMIN`).
- **Assistant IA local (Symfony AI + Ollama)** :
  - Interface web interactive (`/assistant`) pour dialoguer avec des modèles de langage locaux (`llama3.1:latest`, `qwen3.5:9B`, `qwen3.8:27B`).
  - Commande console dédiée (`php bin/console app:assistant`) pour les tests et exécutions scriptées.
  - Exécution 100 % confidentielle en local sur votre machine sans fuite de données vers des API tierces.
  - Inférence asynchrone via `Fetch` sans rechargement de page et protection contre les timeouts.
- **Sécurité & Authentification** :
  - Pare-feu Symfony avec hachage sécurisé des mots de passe.
  - Commande CLI d'initialisation et réinitialisation d'administrateurs (`app:create-admin`).
  - Contrôle d'accès strict sur l'administration et l'assistant IA.

---

## 🛠️ Stack technique

- **Backend** : PHP 8.4+ / PHP 8.5, Symfony 8.1
- **Base de données** : PostgreSQL 16 (via Docker Compose)
- **ORM** : Doctrine ORM 3.7 & Doctrine Migrations 4.0
- **Administration** : EasyAdmin Bundle 5
- **Intelligence Artificielle** : Symfony AI (`symfony/ai-bundle` + `symfony/ai-ollama-platform`)
- **Moteur LLM local** : Ollama (`http://127.0.0.1:11434`)
- **Frontend** : Twig, Tailwind CSS (via Symfonycasts TailwindBundle), DaisyUI, AssetMapper, Stimulus, Turbo
- **Files d'attente** : Symfony Messenger (`symfony/doctrine-messenger`)

---

## 📋 Prérequis

- [PHP 8.4+](https://www.php.net/) avec extensions `pdo_pgsql`, `ctype`, `iconv`
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)
- [Docker & Docker Compose](https://www.docker.com/)
- [Ollama](https://ollama.com/) (pour l'assistant IA local)

---

## ⚡ Installation & Démarrage rapide

### 1. Cloner le projet
```bash
git clone https://github.com/Gw3nhael51/symfonyLearn.git
cd symfonyLearn
```

### 2. Installer les dépendances Composer
```bash
composer install
```

### 3. Démarrer les services Docker (Base PostgreSQL & Mailpit)
```bash
docker compose up -d
```

### 4. Appliquer les migrations de base de données
```bash
symfony console doctrine:migrations:migrate --no-interaction
```

### 5. Créer le compte administrateur
```bash
symfony console app:create-admin admin admin
```

### 6. Démarrer le serveur de développement
```bash
symfony serve -d
```
L'application est disponible à l'adresse : **http://127.0.0.1:8000**

---

## 🧠 Configuration de l'Assistant IA (Ollama)

L'assistant IA s'appuie sur le moteur local **Ollama**.

### 1. Vérifier qu'Ollama est actif
```bash
curl http://127.0.0.1:11434/api/tags
```

### 2. Télécharger les modèles recommandés
```bash
# Modèle rapide et précis (par défaut) :
ollama pull llama3.1:latest

# Modèle de raisonnement avancé :
ollama pull qwen3.5:9B
```

### 3. Tester l'inférence en ligne de commande
```bash
# Avec le modèle par défaut (Llama 3.1) :
php bin/console app:assistant "Explique en 10 mots ce qu'est un SLA."

# En spécifiant un modèle :
php bin/console app:assistant "Donne la définition d'un contrat commercial." -m qwen3.5:9B
```

### 4. Utiliser l'interface Web
1. Rendez-vous sur **http://127.0.0.1:8000/login** et connectez-vous avec vos identifiants admin.
2. Cliquez sur le lien **Assistant IA** dans la barre de navigation ou accédez directement à **http://127.0.0.1:8000/assistant**.
3. Choisissez le modèle, saisissez votre demande (ou cliquez sur une suggestion rapide) et visualisez la réponse instantanément.

---

## 🧪 Tests & Qualité du code

```bash
# Lancer les tests unitaires et fonctionnels (PHPUnit) :
php bin/phpunit
# ou via la CLI Symfony avec injection de l'environnement Docker :
symfony run vendor/bin/phpunit

# Valider le conteneur de services :
php bin/console lint:container

# Valider la syntaxe des templates Twig :
php bin/console lint:twig templates/
```

---

## 📂 Architecture du projet

```
├── config/                 # Configuration Symfony et bundles (AI, Messenger, Security...)
│   └── packages/
│       ├── ai.yaml                    # Configuration globale Symfony AI
│       └── ai_ollama_platform.yaml    # Endpoint et pont vers Ollama local
├── src/
│   ├── Command/
│   │   ├── AssistantCommand.php       # Commande CLI d'interrogation de l'IA
│   │   └── CreateAdminCommand.php     # Commande CLI de création d'administrateurs
│   ├── Controller/
│   │   ├── Admin/                     # Contrôleurs du tableau de bord EasyAdmin
│   │   ├── AssistantController.php    # Interface web et endpoint AJAX de l'assistant IA
│   │   ├── ConferenceController.php   # Consultation publique des conférences
│   │   ├── HomeController.php         # Page d'accueil
│   │   └── SecurityController.php     # Connexion et déconnexion
│   ├── Entity/                        # Entités Doctrine (Admin, Conference, Comment)
│   └── Repository/                    # Répertoires Doctrine
└── templates/
    ├── assistant/
    │   └── index.html.twig            # Vue interactive de l'assistant IA
    ├── conference/
    │   └── index.html.twig            # Vue des conférences
    ├── home/
    │   ├── _navigation.html.twig      # Barre de navigation responsive avec contrôle d'accès
    │   └── index.html.twig            # Template d'accueil EventPulse
    └── security/
        └── login.html.twig            # Formulaire de connexion
```

---

## 📄 Licence

Projet développé à des fins d'apprentissage et de laboratoire technique.
