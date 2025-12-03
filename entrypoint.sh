#!/bin/bash
set -e

# Initialize MariaDB if not already initialized
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Initializing MariaDB data directory..."
    mysql_install_db --user=mysql --datadir=/var/lib/mysql > /dev/null
    
    echo "Starting MariaDB temporarily..."
    mysqld_safe --skip-networking &
    pid="$!"
    
    # Wait for MariaDB to start
    echo "Waiting for MariaDB to start..."
    for i in {30..0}; do
        if echo 'SELECT 1' | mysql &> /dev/null; then
            break
        fi
        sleep 1
    done
    
    if [ "$i" = 0 ]; then
        echo >&2 'MariaDB init process failed.'
        exit 1
    fi

    echo "Creating database and user..."
    mysql <<-EOSQL
        CREATE DATABASE IF NOT EXISTS safesecur;
        CREATE USER IF NOT EXISTS 'safesecur'@'localhost' IDENTIFIED BY 'safesecur';
        GRANT ALL PRIVILEGES ON safesecur.* TO 'safesecur'@'localhost';
        FLUSH PRIVILEGES;
EOSQL

    if [ -f "/app/safesecur.sql" ]; then
        echo "Importing safesecur.sql..."
        mysql safesecur < /app/safesecur.sql
    else
        echo "safesecur.sql not found, skipping import."
    fi

    echo "Stopping temporary MariaDB..."
    if ! kill -s TERM "$pid" || ! wait "$pid"; then
        echo >&2 'MariaDB init process failed.'
        exit 1
    fi
    
    echo "MariaDB initialized successfully."
fi

# Create log directories for supervisor
mkdir -p /var/log/mysql /var/log/node

echo "Starting Supervisord..."
exec "$@"
