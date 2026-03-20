# Gerel Ma 221 - Business Suite

Gerel Ma est une suite ERP minimaliste, ultra-rapide et sécurisée, conçue spécifiquement pour les entrepreneurs et startups visionnaires d'Afrique et d'ailleurs. Elle fluidifie l'expérience de gestion financière, facturation et processus commerciaux avec une interface haut-de-gamme.

## ✨ Fonctionnalités Principales
- **Facturation Éclair** : Création de devis, bons de commande, et factures avec changement automatique des statuts.
- **Journal de Caisse Réinventé** : Analyse fiscale en temps réel, calcul de TVA réversible et balance de BRS.
- **Contrats Intelligents** : Éditeur de modules HTML (WYSIWYG) et Signature manuscrite intégrée via Canvas Pad.
- **Portfolio Clients (CRM)** : Gestion relationnelle claire avec suivi individuel de l'historique et des montants facturés.
- **Interface Premium (SPA)** : Design asymétrique, thèmes chromatiques vibrants ("Notebook Edge", "GoalBucket style") et transitions sans rechargement pour une vélocité maximale.
- **Contrôle d'Accès Réparti** : Profils Admin, Commercial, et Comptable implémentés avec un système d'authentification robuste.

## 🛠 Technique & Architecture (Madeline Framework)
Ce projet est entièrement propulsé par le **Madeline Framework**, un micro-framework PHP "maison" écrit sur-mesure pour être foudroyant de rapidité.

- **Backend** : PHP 8.x + Madeline Framework Architecture (Modulaire / MVC+). 
- **Moteur SPA** : JavaScript natif (`madeline.js`) remplaçant instantanément le cache client pour une sensation d'App Mobile.
- **Stylisation** : Tailwind CSS avec des variables "Unique Spacing" générées localement.
- **Data** : Modulaire (Contrats, Documents, Utilisateurs).

Le Framework est conçu pour un déploiement "Zéro-Config". L'application est **100% Path-Independent** grâce à une résolution intelligente de chemin serveur. Elle fonctionne nativement à la racine d'un domaine ou plongée dans des sous-répertoires sans nécessiter la moindre retouche.

## 🚀 Installation & Exécution
1. Transférez le dépôt entier dans votre serveur (MAMP/XAMPP/Vercel/Apache).
2. Vérifiez que la racine pointe sur la source, ou ouvrez simplement le dossier sous `localhost/gerel-ma-221/`.
3. Profitez directement de l'interface et du routeur automatique.

## 👨‍💻 Crédits
Design concept et développement : **Digital Artists Studio** (Dakar).
Propulsé par le moteur d'entreprise *Gerel Ma*.
Ligne de conduite : "Make it Minimal, Make it Fast".
