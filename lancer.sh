#!/bin/bash

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}╔════════════════════════════════════╗${NC}"
echo -e "${BLUE}║  TechMada RH - Démarrage du serveur  ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════╝${NC}"
echo ""

# Vérifier PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP n'est pas installé${NC}"
    exit 1
fi

echo -e "${GREEN}✓${NC} PHP détecté"

# Vérifier composer
if ! command -v composer &> /dev/null; then
    echo -e "${YELLOW}⚠ Composer n'est pas installé - Installation des dépendances skippée${NC}"
else
    echo -e "${GREEN}✓${NC} Composer détecté"
    echo ""
    echo -e "${YELLOW}Installation des dépendances...${NC}"
    composer install --quiet 2>/dev/null || true
fi

echo ""
echo -e "${YELLOW}Démarrage du serveur...${NC}"
echo ""

# Port défaut
PORT=8080

# Demander le port (optionnel)
echo -e "${YELLOW}Port par défaut: 8080${NC}"
read -p "Appuyer sur Entrée pour continuer (ou taper un port différent): " PORT_INPUT
if [ -n "$PORT_INPUT" ]; then
    PORT=$PORT_INPUT
fi

echo ""
echo -e "${GREEN}════════════════════════════════════${NC}"
echo -e "${GREEN}Serveur lancé sur: http://localhost:$PORT${NC}"
echo -e "${GREEN}════════════════════════════════════${NC}"
echo ""
echo -e "${YELLOW}Identifiants de test:${NC}"
echo -e "${BLUE}Admin:${NC} admin@techmada.mg / password123"
echo -e "${BLUE}RH:${NC} rh@techmada.mg / password123"
echo -e "${BLUE}Employé:${NC} jean.dupont@techmada.mg / password123"
echo ""
echo -e "${YELLOW}Appuyer sur Ctrl+C pour arrêter le serveur${NC}"
echo ""

# Lancer le serveur
php -c .php-server.ini spark serve --port=$PORT
