# SafeSecur

Application web dédiée à la sécurité et à la gestion de services de protection.

## À propos

SafeSecur est une plateforme complète offrant des services dans le domaine de la sécurité, comprenant un catalogue de produits, des actualités et des informations de contact.

## Technologies utilisées

- **Frontend**: Vue.js 3, Bootstrap 5
- **Backend**: Node.js, Express
- **Base de données**: MySQL
- **Autres**: JWT pour l'authentification, Nodemailer pour les emails, Multer pour la gestion des fichiers

## Fonctionnalités

- Catalogue de produits avec catégories
- Section d'actualités
- Page de contact
- Authentification utilisateur
- Interface d'administration pour gérer le contenu

## Installation

1. Cloner le dépôt
```
git clone [URL_DU_REPO]
cd safesecur
```

2. Installer les dépendances
```
npm install
```

3. Configurer la base de données
   - Importer le fichier `safesecur.sql` dans votre serveur MySQL
   - Créer un fichier `.env` à la racine du projet avec les variables d'environnement nécessaires

4. Lancer l'application en développement
```
npm run dev       # Pour le frontend
npm run server    # Pour le backend (dans un autre terminal)
```

## Déploiement

Pour construire l'application pour la production :
```
npm run build
```

## Structure du projet

- `src/` : Code source du frontend Vue.js
- `server/` : Code source du backend Node.js
- `public/` : Ressources publiques
- `uploads/` : Fichiers téléchargés par les utilisateurs

## Contact

Pour plus d'informations, veuillez contacter l'équipe SafeSecur.