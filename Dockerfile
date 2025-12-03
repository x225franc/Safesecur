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
WORKDIR /app/server

COPY server/package.json ./
RUN npm install --only=production

COPY server/ .

RUN mkdir -p /app/server/public
COPY --from=frontend-builder /app/frontend/dist/ /app/server/public/

ENV NODE_ENV=production \
    PORT=3005 \
    ONLINE=false

EXPOSE 3005
CMD ["node", "index.js"]
