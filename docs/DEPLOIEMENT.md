# Mise en ligne sur un hébergement mutualisé (cPanel)

Ce document décrit la mise en ligne du site ESCAT sur un hébergement
mutualisé de type cPanel (LWS, OVH, Hostinger, PlanetHoster…).

Un hébergement mutualisé n'a généralement ni Composer ni Node. Les
dépendances et les feuilles de style compilées sont donc **préparées sur le
poste de travail**, puis téléversées.

---

## 0. Ce que l'hébergement doit fournir

| Exigence | Valeur | Pourquoi |
|---|---|---|
| PHP | **8.2 minimum** | Laravel 12 ne démarre pas en dessous |
| Extensions PHP | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl` | socle Laravel |
| Base de données | MySQL 8 ou MariaDB 10.6+ | |
| Racine du domaine | modifiable, **ou** accès à `public_html` | voir l'étape 4 |

La version de PHP se règle dans cPanel : **Select PHP Version** (ou
*MultiPHP Manager*). C'est là aussi qu'on active les extensions.

> L'extension **GD** n'est pas nécessaire en production : elle ne sert
> qu'aux tests, pour fabriquer de fausses images.

---

## 1. Préparer le paquet à téléverser

Depuis la racine du projet, **dans Git Bash** :

```bash
bash deploy/preparer-livraison.sh
```

> Git Bash, et non PowerShell : Composer a besoin de dézipper les archives
> qu'il télécharge, or l'extension `zip` n'est pas activée dans
> `C:\xampp\php\php.ini`. Git Bash fournit `unzip`, ce qui suffit à
> Composer ; PowerShell, lui, échoue à mi-parcours.

Le script produit un dossier `deploy/livraison/` contenant l'application
prête à partir. Il enchaîne :

1. `composer install --no-dev --optimize-autoloader` — les dépendances sans
   les outils de développement ;
2. `npm run build` — les feuilles de style et scripts compilés, dans
   `public/build` (ce dossier est hors dépôt : il **doit** être reconstruit
   à chaque livraison) ;
3. la copie des fichiers utiles, en écartant `node_modules`, `tests`,
   `legacy`, `.git`, `.env` et le lien `public/storage` ;
4. la restauration des dépendances de développement sur le poste, pour que
   les tests continuent de tourner — y compris si le script échoue en
   route.

Comptez environ **165 Mo**, dont 128 Mo pour `public/assets` : c'est la
bibliothèque d'images du gabarit, dont une bonne part n'est pas utilisée
par le site. Un tri préalable raccourcirait nettement le téléversement.

---

## 2. La base de données

Dans cPanel, **MySQL Databases** :

1. créez une base (ex. `escat_prod`) ;
2. créez un utilisateur avec un mot de passe long et unique ;
3. rattachez l'utilisateur à la base avec **ALL PRIVILEGES**.

Notez les trois valeurs : elles vont dans le `.env` à l'étape suivante. Sur
la plupart des hébergeurs le nom réel est préfixé par le compte
(`moncompte_escat_prod`).

---

## 3. Le fichier `.env` de production

Partez de `.env.production.example`, renommez-le en `.env`, et remplissez.
Les points qui ne se devinent pas :

```ini
APP_ENV=production
APP_DEBUG=false          # laisser false : sinon la moindre erreur affiche
                         # le code source et les identifiants
APP_URL=https://www.escat.cg
```

La clé d'application doit être **propre à la production**. Générez-la sur le
poste de travail et recopiez-la :

```bash
php artisan key:generate --show
```

> Ne reprenez pas la clé de développement : elle chiffre les sessions et les
> cookies.

Pour l'envoi des courriels (formulaire de contact), renseignez le compte
SMTP fourni par l'hébergeur, créé dans **Email Accounts** :

```ini
MAIL_MAILER=smtp
MAIL_HOST=mail.escat.cg
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=contact@escat.cg
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=contact@escat.cg
MAIL_CONTACT_RECIPIENT=...   # destinataire des demandes du formulaire
```

---

## 4. Où poser les fichiers

Laravel expose **uniquement** son dossier `public/`. Tout le reste — le
`.env`, le code, les dépendances — doit rester hors d'atteinte du Web.

### Variante A — déplacer la racine du domaine (recommandée)

Téléversez `livraison/` dans `/home/COMPTE/escat/`, puis dans cPanel,
**Domains** → le domaine → *Document Root*, indiquez :

```
/home/COMPTE/escat/public
```

Rien d'autre à modifier. C'est la disposition la plus sûre et la plus simple
à remettre à jour.

### Variante B — si la racine n'est pas modifiable

Certains hébergeurs imposent `public_html`. Dans ce cas :

1. téléversez tout **sauf** `public/` dans `/home/COMPTE/escat/` ;
2. téléversez le **contenu** de `public/` dans `/home/COMPTE/public_html/` ;
3. ouvrez `public_html/index.php` et corrigez les trois chemins :

```php
if (file_exists($maintenance = __DIR__.'/../escat/storage/framework/maintenance.php')) {
require __DIR__.'/../escat/vendor/autoload.php';
$app = require_once __DIR__.'/../escat/bootstrap/app.php';
```

Vérifiez que `public_html/.htaccess` est bien présent : c'est lui qui
réécrit les URL. Les transferts FTP ignorent souvent les fichiers dont le
nom commence par un point — pensez à afficher les fichiers cachés dans
FileZilla (*Serveur* → *Forcer l'affichage des fichiers cachés*).

---

## 5. Droits des dossiers

Deux dossiers doivent être inscriptibles par PHP :

```
storage/              et tout son contenu
bootstrap/cache/
```

En cPanel, *File Manager* → clic droit → **Change Permissions** : `755`
convient presque partout. Ne mettez `775` que si le site renvoie une erreur
d'écriture, et jamais `777`.

---

## 6. Terminer l'installation

Si l'hébergement offre un **Terminal** (cPanel) ou un accès **SSH**,
placez-vous dans le dossier de l'application et lancez :

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- `migrate --force` crée les tables. `--force` est requis : Laravel refuse
  autrement de migrer en production.
- `storage:link` relie `public/storage` aux fichiers téléversés depuis
  l'administration (images des actualités, de l'équipe…). **Sans ce lien,
  toutes ces images seront brisées.**
- les trois `:cache` précalculent la configuration, les routes et les
  gabarits. Ils font une différence nette sur un mutualisé.

### Sans accès au terminal

- **Migrations** : exportez votre base locale en SQL (`mysqldump`, ou
  phpMyAdmin → *Exporter*), puis importez le fichier dans phpMyAdmin côté
  hébergeur. Exportez **la structure et les données** si vous voulez aussi
  reprendre le contenu déjà saisi.
- **`storage:link`** : le lien symbolique peut être créé à la main dans
  *File Manager*, ou remplacé par une copie du dossier `storage/app/public`
  vers `public/storage` — à recopier après chaque ajout d'image, ce qui est
  pénible : préférez un hébergement avec SSH.
- **Les caches** : lancez les trois commandes sur le poste de travail **avec
  le `.env` de production**, puis téléversez `bootstrap/cache/`. Attention,
  `config:cache` fige les valeurs du `.env` : refaites-le à chaque
  changement.

---

## 7. Vérifier

1. La page d'accueil s'affiche, feuilles de style comprises.
2. Le **mode sombre** se maintient d'une page à l'autre (c'est le défaut
   corrigé récemment : il se vérifie en changeant de page après avoir
   basculé).
3. Le **formulaire de contact** aboutit en **un seul envoi**, et la demande
   apparaît dans l'administration.
4. Une image téléversée depuis l'administration s'affiche sur le site
   public — c'est le test du lien `storage`.
5. `https://` est bien actif et force la redirection (cPanel → **SSL/TLS
   Status**, puis *Force HTTPS Redirect* dans *Domains*).
6. Une URL inexistante renvoie une page 404 du site, **pas** une trace
   d'erreur Laravel. Si vous voyez du code, `APP_DEBUG` est resté à `true`.

---

## 8. Les mises à jour suivantes

```bash
bash deploy/preparer-livraison.sh
```

Puis téléversez en écrasant, **sans toucher** :

- `.env`
- `storage/app/public/` (les images téléversées)
- `storage/logs/`

Et relancez les caches :

```bash
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Pour une modification purement visuelle, `public/build/` et `view:cache`
suffisent.

> Pensez à `php artisan down` avant et `php artisan up` après si la
> livraison comporte des migrations.
