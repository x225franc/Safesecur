# SAFESECUR

## Présentation

SAFESECUR est une application web pour une entreprise spécialisée dans les solutions de sécurité, la protection incendie et les systèmes de contrôle d'accès pour professionnels.

## Fonctionnalités

- Site vitrine présentant les services et l'expertise de l'entreprise
- Catalogue de produits avec système de gestion
- Section actualités pour partager les dernières nouvelles
- Système de contact avec formulaire
- Interface d'administration sécurisée par JWT

## Technologies utilisées

- **Frontend**: Vue.js 3, Bootstrap 5
- **Backend**: Express.js, Node.js
- **Base de données**: MySQL
- **Authentification**: JWT (JSON Web Tokens)

## Installation

1. Cloner le dépôt
2. Installer les dépendances `npm install`
3. Configurer le fichier `.env` avec vos informations de connexion à la base de données
4. Lancer le serveur de développement `npm run dev`
5. Lancer le serveur backend `npm run server`

## Développement

- `npm run dev` - Lance le serveur de développement frontend
- `npm run server` - Lance le serveur backend
- `npm run build` - Compile le projet pour la production

## Structure du projet

- `/server` - Code du serveur backend
- `/src` - Code source du frontend
  - `/assets` - Images et styles
  - `/components` - Composants Vue réutilisables
  - `/views` - Pages de l'application
  - `/router` - Configuration des routes

## Contact

Pour plus d'informations, contactez SafeSecur à contact@safesecur.com