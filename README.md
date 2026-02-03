# Hagrosoire - Application de gestion d'arrosage intelligent

Application Symfony pour la gestion intelligente de l'arrosage de zones vertes. Elle permet de calculer les besoins en eau des espaces verts en fonction des donnees meteorologiques et des caracteristiques des zones.

## Fonctionnalites

- **Gestion des zones** : Creation et configuration de zones d'arrosage avec coordonnees GPS, surface et type d'espace
- **Donnees meteorologiques** : Recuperation automatique des previsions meteo via l'API Meteo Concept
- **Algorithme hydrolique** : Calcul automatique des besoins en eau (ETc, stock hydrique, decision d'arrosage)
- **Planning hebdomadaire** : Configuration des jours et horaires d'arrosage par zone
- **Capteurs connectes** : Integration avec des capteurs d'humidite externes via API
- **Carte interactive** : Visualisation des zones sur une carte Leaflet

## Prerequis

- PHP 8.4 ou superieur
- Composer
- Node.js et npm
- MySQL ou MariaDB
- Serveur web (Apache/Nginx)

## Installation

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd Hagrosoire
```

### 2. Installer les dependances PHP

```bash
composer install
```

### 3. Installer les dependances JavaScript

```bash
npm install
```

### 4. Configurer l'environnement

Copier le fichier `.env` en `.env.local` et configurer les variables :

```bash
cp .env .env.local
```

Modifier `.env.local` avec vos parametres :

```env
# Base de donnees
DATABASE_URL="mysql://user:password@127.0.0.1:3306/hagrosoire?serverVersion=8.0"

# API Capteurs (optionnel)
SENSOR_API_URL=https://votre-api-capteurs.com/
```

### 5. Creer la base de donnees

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 6. Compiler les assets

```bash
npm run build
```

Pour le developpement avec hot reload :

```bash
npm run watch
```

### 7. Lancer le serveur de developpement

```bash
symfony server:start
```

Ou avec le serveur PHP integre :

```bash
php -S localhost:8000 -t public
```

## Structure du projet

```
Hagrosoire/
├── assets/              # Assets JavaScript/CSS (Stimulus, Tailwind)
├── config/              # Configuration Symfony
├── migrations/          # Migrations Doctrine
├── public/              # Point d'entree web
├── src/
│   ├── Command/         # Commandes console
│   ├── Controller/      # Controleurs
│   ├── Entity/          # Entites Doctrine
│   ├── Form/            # Types de formulaires
│   ├── Repository/      # Repositories Doctrine
│   └── Service/         # Services metier
├── templates/           # Templates Twig
└── tests/               # Tests
```

## Entites principales

- **Zone** : Zone d'arrosage (nom, surface, coordonnees, type d'espace)
- **Sensor** : Capteur d'humidite connecte
- **Meteo** : Donnees meteorologiques journalieres
- **HydroliqueSum** : Bilan hydrolique (ETc, stock, decision)
- **WeeklyDecision** : Planning hebdomadaire d'arrosage

## Commandes console

```bash
# Calculer les bilans hydroliques pour toutes les zones
php bin/console app:hydrolique:calculate

# Synchroniser les capteurs depuis l'API externe
php bin/console app:sensor:sync
```

## Configuration Cron (production)

Pour automatiser les calculs quotidiens, ajouter au crontab :

```cron
# Calcul hydrolique quotidien a 6h
0 6 * * * cd /path/to/project && php bin/console app:hydrolique:calculate

# Synchronisation capteurs toutes les heures
0 * * * * cd /path/to/project && php bin/console app:sensor:sync
```

## Deploiement en production

### 1. Configurer l'environnement de production

```bash
APP_ENV=prod
APP_DEBUG=0
```

### 2. Installer les dependances sans dev

```bash
composer install --no-dev --optimize-autoloader
npm run build
```

### 3. Vider le cache

```bash
php bin/console cache:clear --env=prod
```

### 4. Executer les migrations

```bash
php bin/console doctrine:migrations:migrate --env=prod
```

## Technologies utilisees

- **Backend** : Symfony 6.4, Doctrine ORM
- **Frontend** : Twig, Tailwind CSS, Stimulus
- **Carte** : Leaflet.js
- **Base de donnees** : MySQL/MariaDB

## API externes

- **Meteo Concept** : Previsions meteorologiques (temperature, pluie, vent, humidite)
- **API Capteurs** : Donnees des capteurs d'humidite connectes

## Auteurs

Projet realise dans le cadre de la SAE 501 - MMI Unistra
