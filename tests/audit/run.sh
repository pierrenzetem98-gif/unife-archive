#!/usr/bin/env bash
# Lance l'audit sur une base SQLite temporaire. MySQL n'est jamais touché.
set -u
cd "$(dirname "$0")/../.."

DB=/tmp/unife-audit.sqlite
rm -f "$DB"
touch "$DB"

export DB_CONNECTION=sqlite
export DB_DATABASE="$DB"
export APP_ENV=testing
export CACHE_STORE=array

php artisan config:clear > /dev/null

echo "Création des tables dans la base temporaire..."
if ! php artisan migrate --force > /dev/null; then
    echo "Échec des migrations."
    rm -f "$DB"
    exit 2
fi

php tests/audit/audit.php
STATUS=$?

rm -f "$DB" storage/app/public/exams/audit-sample.pdf
exit $STATUS