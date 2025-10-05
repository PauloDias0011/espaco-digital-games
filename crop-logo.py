#!/usr/bin/env python3
"""
Script para fazer crop automático da logo removendo áreas em branco/transparentes
"""
from PIL import Image
import sys

def crop_image(input_path, output_path):
    try:
        # Abrir imagem
        img = Image.open(input_path)
        
        print(f"📸 Imagem original: {img.size[0]}x{img.size[1]} pixels")
        
        # Converter para RGBA se necessário
        if img.mode != 'RGBA':
            img = img.convert('RGBA')
        
        # Pegar bounding box (remove transparência/branco)
        bbox = img.getbbox()
        
        if bbox:
            # Fazer crop
            img_cropped = img.crop(bbox)
            
            print(f"✂️  Imagem cortada: {img_cropped.size[0]}x{img_cropped.size[1]} pixels")
            
            # Adicionar padding de 10px
            padding = 10
            new_size = (img_cropped.size[0] + padding * 2, img_cropped.size[1] + padding * 2)
            img_final = Image.new('RGBA', new_size, (255, 255, 255, 0))
            img_final.paste(img_cropped, (padding, padding))
            
            # Salvar
            img_final.save(output_path, 'PNG', optimize=True)
            
            print(f"✅ Imagem salva: {output_path}")
            print(f"📦 Tamanho final: {img_final.size[0]}x{img_final.size[1]} pixels (com padding)")
            
            # Calcular redução de tamanho
            import os
            original_size = os.path.getsize(input_path)
            new_size = os.path.getsize(output_path)
            reduction = ((original_size - new_size) / original_size) * 100
            
            print(f"💾 Tamanho do arquivo:")
            print(f"   Original: {original_size / 1024 / 1024:.2f} MB")
            print(f"   Cortado: {new_size / 1024 / 1024:.2f} MB")
            if reduction > 0:
                print(f"   Redução: {reduction:.1f}%")
            
            return True
        else:
            print("❌ Não foi possível encontrar área para crop")
            return False
            
    except Exception as e:
        print(f"❌ Erro: {e}")
        return False

if __name__ == "__main__":
    input_file = "public/images/logomarca-original.png"
    output_file = "public/images/logomarca.png"
    
    print("=" * 50)
    print("  Crop Automático - Logo Espaço Digital")
    print("=" * 50)
    print()
    
    if crop_image(input_file, output_file):
        print()
        print("🎉 Crop realizado com sucesso!")
    else:
        print()
        print("⚠️  Não foi possível fazer o crop")
        sys.exit(1)
