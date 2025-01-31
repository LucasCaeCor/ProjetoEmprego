#!/usr/bin/env bash
echo "Este script foi feito para subir ao Docker Hub uma imagem de container Docker construida localmente, sem necessitar do docker-compose"

## Trocar nome_usuario pelo seu nome de usuário no Docker Hub
nome_usuario='lucasBruxo0'

## Trocar nome_imagem pelo nome de seu repositório no Docker Hub
nome_imagem='lucasbruxo/siteemphp:'

## Trocar somente se for versionar a imagem do repositório Docker Hub. Default: "latest"
versao="latest"

## Na linha abaixo executa a ação de construção da imagem
docker push ${nome_usuario}/${nome_imagem}:${versao}

echo "Processo concluído!"