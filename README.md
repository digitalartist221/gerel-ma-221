<div align="center">
  
# Madeline Framework
**Standard Industriel pour Applications PHP 8.3+**

[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

*Stabilité. Vitesse. Sémantique.*

</div>

Madeline est une infrastructure logicielle PHP moderne conçue pour la robustesse et la scalabilité. Elle élimine la surcharge cognitive grâce à un typage strict et une sémantique Wolof intégrée.

---

## 🏛️ Architecture Core
- **Cycle Synchrone** : Exécution ultra-rapide sans surcharge de services inutiles.
- **Turbo SPA Engine v6.5** : Navigation asynchrone native avec injection de styles.
- **Shield Security** : Protection multicouche (CSP, HSTS, XSS, CSRF).
- **Zéro-Migration ORM** : Votre base de données suit votre code, pas l'inverse.

---

## 🚀 Démarrage Rapide

1. **Installation** :
   Déployez le framework et lancez le serveur :
   ```bash
   php ligeey doxal
   ```

2. **Configuration** :
   Accédez à `http://localhost:8000/setup` pour configurer vos accès SQL via l'assistant **Rek**.

---

## 🛠️ Ligeey CLI Reference

### Scaffolding (Amal)
- `php ligeey amal:crud [Entity]` : Suite complète MVC + Vues.
- `php ligeey amal:controller [Name]` : Classe contrôleur structurée.
- `php ligeey amal:model [Name]` : Modèle ORM synchronisé.

### Maintenance & Yoon
- `php ligeey setal:cache` : Purge des templates et métadonnées SQL.
- `php ligeey yoon:run` : Exécution des migrations manuelles.
- `php ligeey tahl:auth` : Installation du bouclier d'authentification.
- `php ligeey takoo:storage` : Liaison du dossier public/storage.

---

## 📡 MadelineORM (Wolof)

```php
// Sémantique intuitive
$user->fari(['age' => 25]); // Find
$user->bindu();             // Insert/Save
$user->weccit();            // Update
$user->far();               // Delete
$user->loko(1);             // Get by ID
```

---

## 🔒 Sécurité Industrielle
Madeline intègre par défaut le middleware `SecurityHeadersMiddleware` :
- **Content Security Policy** (CSP) configuré.
- **HSTS** automatique sur HTTPS.
- **Anti-Clickjacking** (X-Frame-Options).
- **Jetons CSRF** via `@csrf`.

---
### *Madeline - L'excellence logicielle signée Digital Artist Studio.*
