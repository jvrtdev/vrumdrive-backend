# Locadora de Veículos - VrumDrive

Este repositório contém o código-fonte do backend da plataforma de locação de veículos. O objetivo deste projeto é fornecer uma solução robusta e escalável para gerenciar a locação de veículos, incluindo autenticação de usuários, gerenciamento de veículos e reservas.
## Tecnologias Utilizadas

    Linguagem de Programação: PHP
    Framework: Slim-Framework
    Banco de Dados: MySQL
    Arquitetura: Design Patterns
    Serviços na Nuvem: AWS (Amazon Web Services), EC2, RDS, S3

## Funcionalidades

    Autenticação de Usuários: Sistema de login e registro seguro.
    Gerenciamento de Veículos: Adição, edição e remoção de veículos disponíveis para locação.
    Reservas: Criação, edição e cancelamento de reservas de veículos.
    Proteção de Rotas: Acesso seguro às rotas do sistema, garantindo a proteção dos dados dos usuários.

## Requisitos

    PHP 7.4 ou superior
    Composer
    MySQL
    AWS CLI configurado (para serviços na nuvem)
    (A API ATUALMENTE ESTÁ FORA DO AR)

## Instalação

    Clone este repositório:

    bash

git clone https://github.com/seu-usuario/locadora-veiculos-backend.git
cd locadora-veiculos-backend

## Instale as dependências:

bash

composer install

Configure o arquivo .env com suas credenciais do banco de dados e AWS.

Execute as migrações do banco de dados:

bash

php artisan migrate

## Inicie o servidor de desenvolvimento:

bash

    php -S localhost:8080 -t public

## Uso
Endpoints Principais

    Autenticação
        POST /api/user/login - Login de usuário
        POST /api/user/create - Registro de novo usuário

    Veículos
        GET /api/vehicles - Listar todos os veículos
        POST /api/vehicles - Adicionar novo veículo
        PUT /api/vehicles/{id} - Editar veículo
        DELETE /api/vehicles/{id} - Remover veículo

    Reservas
        GET /api/bookings - Listar todas as reservas
        POST /api/booking - Criar nova reserva
        PUT /api/booking/{id} - Editar reserva
        DELETE /api/booking/{id} - Cancelar reserva

Contribuição

    Faça um fork deste repositório.
    Crie uma branch para sua feature ou correção de bug (git checkout -b feature/nome-da-feature).
    Commit suas alterações (git commit -m 'Adicionar nova feature').
    Faça um push para a branch (git push origin feature/nome-da-feature).
    Abra um Pull Request.

Licença

Este projeto está licenciado sob a MIT License.
Contato

Para mais informações, entre em contato através do e-mail: jvrtdev@gmail.com.
