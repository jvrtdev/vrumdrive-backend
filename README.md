<h1>Locadora de Veículos - Backend</h1>
<p>Este é o backend de uma aplicação de locadora de veículos, desenvolvido em PHP utilizando o Slim Framework.</h2>


<h2>Requisitos</h2>

<li>PHP 7.0 ou superior</li>
<li>Composer (para gerenciamento de dependências)</li>

<h2>Instalação</h2>

1 - Clone este repositório para o seu ambiente local:

<code>git clone https://github.com/jvrtdev/backend-php.git</code>
<br><br><br>
2 - Navegue até o diretório do projeto:

<code>cd locadora-backend</code>
<br><br><br>
3 - Instale as dependências do projeto utilizando o Composer:

<code>composer install</code>
<br><br><br>
4 - Configuração:

<p>Antes de executar o projeto, você precisa configurar algumas variáveis de ambiente. Renomeie o arquivo .env.example para .env e configure as variáveis de ambiente conforme necessário.</p>
<br><br><br>
<h4>Exemplo de .env:</h4>
<code>
DB_HOST=localhost
DB_NAME=nomebd
DB_USER=usuario
DB_PASS=senha
</code>

<h4>Uso:</h4>

Após a instalação e configuração, você pode iniciar o servidor local para executar o backend:

<code>php -S localhost:8000 -t public</code>
O backend estará acessível em http://localhost:8000.

                               


Licença
Este projeto está licenciado sob a Licença MIT.

