# Mink project

Version 1 d'un tableau de bord pour la présentation et la vente de bétail, avec un espace d'administration dédié pour gérer le tableau de bord.

## Mise en place

### Prérequis

1. **Installer PHP et Laravel**

   Utilisez la commande suivante pour installer PHP sur Windows :
   ```bash
   Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows'))
   ```

2. **Installer Node.js et NPM**

   Téléchargez et installez la version LTS de Node.js (inclut NPM) en visitant le site officiel : [Node.js - LTS](https://nodejs.org/fr)

3. **Configurer les variables d’environnement**

   Ajoutez les chemins de PHP et Composer à la variable d'environnement `PATH` de votre système.


### Installation des dépendances / projet

1. **Cloner le projet**

   Clonez le projet et accédez au dossier de votre application.

2. **Installer les dépendances PHP**

   Dans le dossier du projet, exécutez la commande suivante pour installer les dépendances Composer :

   ```bash
   composer install
   ```

3. **Configurer le fichier d’environnement**

   Dupliquez le fichier `.env.example` et renommez-le `.env`. 

   Pour désactiver le mode débogage, changez la variable :

   ```env
   APP_DEBUG=false
   ```

4. **Générer la clé d'application**

   Pour générer une clé unique de l'application, exécutez :

   ```bash
   php artisan key:generate
   ```

5. **Configurer la base de données**

   Créez les tables de la base de données SQLite :

   ```bash
   php artisan migrate
   ```

6. **Exécuter les seeders**

   Pour insérer des enregistrements prédéfinis dans la base de données (sans images), exécutez :

   ```bash
   php artisan db:seed
   ```

7. **Lier le stockage public**

   Pour rendre le dossier des images accessible publiquement, utilisez la commande suivante :

   ```bash
   php artisan storage:link
   ```

8. **Installer les dépendances NPM**

   Installez les dépendances front-end avec la commande suivante :

   ```bash
   npm i
   ```


### Configuration de PHP

Modifiez le fichier `php.ini` pour ajuster les paramètres suivants :

Chemin du fichier `C:\Users\%USERPROFILE%\.config\herd-lite\bin`

   ```ini
   variables_order = "GPCS"
   opcache.enable = 1
   opcache.enable_cli = 1
   upload_max_filesize = 10M
   post_max_size = 20M
   max_file_uploads = 10
   max_execution_time = 120
   memory_limit = 256M
   ```


## Déploiement

Ouvrez deux terminaux et exécutez les commandes suivantes :

Dans le premier terminal :

```bash
npm run dev
```

Dans le second terminal :

```bash
php artisan serve
```

## Authors

- [@machurui](https://www.github.com/machurui)

  
