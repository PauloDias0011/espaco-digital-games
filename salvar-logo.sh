#!/bin/bash

# Script para salvar a logo do Espaço Digital
# Execute este script após salvar a imagem da logo em ~/Downloads/

LOGO_SOURCE="$HOME/Downloads/espaco-digital-logo.png"
LOGO_DEST="/home/paulodias/workspace/Espaco Digital Games/public/images/branding/espaco-digital-logo.png"

echo "========================================"
echo "  Instalador de Logo - Espaço Digital"
echo "========================================"
echo ""

# Verificar se o arquivo existe no Downloads
if [ -f "$LOGO_SOURCE" ]; then
    echo "✓ Logo encontrada em Downloads!"
    cp "$LOGO_SOURCE" "$LOGO_DEST"
    echo "✓ Logo copiada com sucesso!"
    echo ""
    echo "Arquivo salvo em:"
    echo "$LOGO_DEST"
    echo ""
    ls -lh "$LOGO_DEST"
    echo ""
    echo "✓ Pronto! Atualize o navegador para ver a logo."
else
    echo "✗ Logo não encontrada em Downloads."
    echo ""
    echo "Por favor:"
    echo "1. Salve a imagem da logo como 'espaco-digital-logo.png'"
    echo "2. Coloque em: ~/Downloads/"
    echo "3. Execute este script novamente"
    echo ""
    echo "Ou copie manualmente para:"
    echo "$LOGO_DEST"
fi

echo ""
echo "========================================"
