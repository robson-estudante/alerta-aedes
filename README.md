# 🦟 SISTEMA DE MAPEAMENTO E ALERTA DE FOCOS DE DENGUE (ALERTA AEDES)


## 📖 Descrição do Projeto
O **Alerta Aedes** é um sistema web desenvolvido como Projeto Interdisciplinar para o Seminário Tech. O objetivo da aplicação é modernizar o combate à dengue, permitindo que cidadãos (moradores) registrem denúncias geolocalizadas de focos do mosquito *Aedes aegypti*, anexando evidências em foto. Simultaneamente, o sistema fornece aos Agentes de Endemias um painel de controle com mapa interativo em tempo real para gestão, triagem e resolução ágil das ocorrências.

## 🚀 Tecnologias Utilizadas
* **Front-end:** HTML5, CSS3 (Efeito Glassmorphism), JavaScript.
* **Mapas e Geolocalização:** Leaflet.js com integração de tiles do Google Maps (Satélite/Lotes) e Geocodificação Reversa (Nominatim).
* **Back-end:** PHP (Sessões e Autenticação).
* **Banco de Dados:** MySQL (Relacional).
* **Servidor Local:** XAMPP (Apache).

## ⚙️ Como executar o sistema localmente
1. Certifique-se de ter o **XAMPP** instalado em sua máquina.
2. Clone este repositório ou baixe o arquivo `.zip` e extraia o conteúdo dentro da pasta `C:\xampp\htdocs\alerta_aedes\`.
3. Abra o painel do XAMPP e inicie os módulos **Apache** e **MySQL**.
4. Acesse o `phpMyAdmin` (http://localhost/phpmyadmin) e crie um banco de dados chamado `alerta_aedes`.
5. Importe o arquivo `Modelo_Fisico_AlertaAedes.sql` (disponível na raiz do projeto) para criar as tabelas e relacionamentos.
6. Abra o navegador e acesse: `http://localhost/alerta_aedes/`.

## ✨ Funcionalidades Implementadas
* Cadastro de novos usuários (Cidadãos).
* Sistema de Login com controle de acesso por perfis (Morador vs. Agente).
* Interface de registro de denúncias com upload de fotos.
* Captura automática de Latitude/Longitude via clique no mapa e conversão para endereço em texto.
* Dashboard do Agente com mapa global de focos pendentes (Pinos Vermelhos) e resolvidos (Pinos Verdes).
* Listagem de histórico pessoal para o morador.
* Edição de status de denúncia (CRUD completo).

## 👥 Integrantes do Grupo

* JANIO WESLEY LOPES CALIXTO 
* ROBSON DA SILVA FERREIRA
* ROMARIO FERNANDES GOMES 
* RYAN DE SOUSA SANTOS 
* VALÉRIO OLIVEIRA LIMA JÚNIOR 
