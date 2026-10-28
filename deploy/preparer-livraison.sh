#!/usr/bin/env bash
#
# Prépare le paquet à téléverser sur l'hébergement mutualisé.
#
# Un mutualisé n'a ni Composer ni Node : les dépendances et les feuilles de
# style compilées doivent donc partir d'ici, déjà construites.
#
# À lancer depuis Git Bash. PowerShell ne convient pas : Composer y réclame
# l'extension zip, absente de ce PHP, alors que Git Bash fournit `unzip`.
#
# Le résultat atterrit dans deploy/livraison/. Voir docs/DEPLOIEMENT.md.

set -euo pipefail

racine="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
livraison="$racine/deploy/livraison"

cd "$racine"

etape() {
    printf '\n==> %s\n' "$1"
}

# --- Garde-fous -----------------------------------------------------------

for outil in composer npm php tar unzip; do
    if ! command -v "$outil" > /dev/null 2>&1; then
        echo "Erreur : $outil est introuvable." >&2
        echo "Lancez ce script depuis Git Bash, pas depuis PowerShell." >&2
        exit 1
    fi
done

if [ -n "$(git status --porcelain)" ]; then
    echo
    echo "Attention : des modifications ne sont pas commitées. Le paquet"
    echo "partira avec, mais le dépôt ne gardera pas trace de ce qui a été"
    echo "mis en ligne."
fi

# Sans les outils de développement, le poste ne peut plus lancer les tests.
# On les remet quoi qu'il arrive, y compris si le script échoue en route.
remettre_les_outils() {
    etape 'Restauration des dépendances de développement'
    composer install --no-interaction || {
        echo "Échec : relancez 'composer install' à la main." >&2
    }
}
trap remettre_les_outils EXIT

# --- Construction ---------------------------------------------------------

etape 'Dépendances PHP, sans les outils de développement'
composer install --no-dev --optimize-autoloader --no-interaction

etape 'Feuilles de style et scripts'
npm run build

# --- Assemblage -----------------------------------------------------------

etape "Assemblage dans deploy/livraison"

rm -rf "$livraison"
mkdir -p "$livraison"

# tar plutôt que cp : il n'emprunte pas les liens symboliques et ses
# exclusions sont fiables.
#
# public/storage est écarté expressément. C'est le lien vers les images
# téléversées depuis l'administration — 140 Mo ici, et surtout le contenu
# du serveur, qu'un téléversement par-dessus écraserait. Le lien se
# recrée avec `php artisan storage:link`.
#
# bootstrap/cache part vide : ces caches figent les valeurs du .env local.
tar -cf - \
    --exclude='public/storage' \
    --exclude='bootstrap/cache/*.php' \
    app bootstrap config database public resources routes vendor \
    artisan composer.json composer.lock .env.production.example \
  | ( cd "$livraison" && tar -xf - )

# storage/ part en squelette : les images téléversées et les journaux
# appartiennent au serveur.
for dossier in \
    app/private \
    app/public \
    framework/cache/data \
    framework/sessions \
    framework/views \
    logs
do
    mkdir -p "$livraison/storage/$dossier"
done

# --- Compte rendu ---------------------------------------------------------

taille="$(du -sh "$livraison" 2>/dev/null | cut -f1)"

cat <<FIN

Paquet prêt.
    deploy/livraison    $taille

Ne pas téléverser par-dessus, sur une mise à jour :
    .env, storage/app/public/, storage/logs/

Suite de la procédure : docs/DEPLOIEMENT.md
FIN
