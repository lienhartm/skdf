#!/bin/bash

set -e

echo "▶ Démarrage des conteneurs..."
docker compose up -d --build

echo "✅ Build terminé"
