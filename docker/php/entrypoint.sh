#!/bin/bash
set -e

echo "=== LeadX Backend Setup ==="

if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -f "config/jwt/private.pem" ]; then
    echo "Generating JWT keys..."
    mkdir -p config/jwt
    openssl genpkey -algorithm RSA -out config/jwt/private.pem \
        -aes256 -pass pass:"${JWT_PASSPHRASE}" \
        -pkeyopt rsa_keygen_bits:4096 2>/dev/null
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem \
        -pubout -passin pass:"${JWT_PASSPHRASE}" 2>/dev/null
    chmod 644 config/jwt/private.pem
    echo "JWT keys generated."
fi

echo "Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

echo "=== Setup complete ==="
exec "$@"
