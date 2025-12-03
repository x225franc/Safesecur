## Dockerfile pour construire et servir l'application fullstack
FROM node:20-bullseye AS frontend-builder
WORKDIR /app

COPY package.json ./
COPY frontend/package.json frontend/package.json
COPY server/package.json server/package.json

RUN npm install --workspaces --ignore-scripts

COPY frontend ./frontend
RUN npm run --workspace frontend build

FROM node:20-bullseye AS server
WORKDIR /app

# Installation de MariaDB et Supervisor
RUN apt-get update && apt-get install -y \
    mariadb-server \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Configuration des répertoires MariaDB
RUN mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chmod 777 /var/run/mysqld

WORKDIR /app/server

COPY server/package.json ./
RUN npm install --only=production

COPY server/ .

RUN mkdir -p /app/server/public
COPY --from=frontend-builder /app/frontend/dist/ /app/server/public/

# Copier la configuration et les scripts
COPY safesecur.sql /app/safesecur.sql
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Variables d'environnement
ENV NODE_ENV=production \
    PORT=3005 \
    ONLINE=false

# Exposer les ports
EXPOSE 3005

# Volume pour la persistance de MariaDB
VOLUME ["/var/lib/mysql"]

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
