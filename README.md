# Documentação da API

## Visão Geral

Esta API possui diversas rotas, segmentadas para diferentes tipos de usuários: administradores, usuários comuns e gerenciamento de reservas. Além disso, há rotas específicas para registro de logs do sistema.

---

## Rotas do Usuário Admin

### Listar todos os usuários
**GET** `/api/admin/users`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Retorna uma lista com todos os usuários cadastrados no sistema.

**Resposta de Sucesso:**
- **Status:** 200 OK
- **Corpo:** Lista de usuários (formato JSON)

### Deletar usuário pelo ID
**DELETE** `/api/admin/user/{id}`

**Headers:** `Authorization: Bearer token`: string 


**Descrição:** Deleta um usuário específico baseado no seu ID.

**Parâmetros de URL:**
- `id` (integer): ID do usuário a ser deletado.

**Resposta de Sucesso:**
- **Status:** 200 OK

### Listar usuário pelo ID
**GET** `/api/admin/{id}`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Retorna as informações de um usuário específico baseado no seu ID.

**Parâmetros de URL:**
- `id` (integer): ID do usuário a ser listado.

**Resposta de Sucesso:**
- **Status:** 200 OK
- **Corpo:** Dados do usuário (formato JSON)

### Upload de imagem do veículo
**POST** `/api/admin/vehicle/{id}`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Faz o upload de uma imagem de um veículo associado ao ID do veículo.

**Parâmetros de URL:**
- `id` (integer): ID do veículo.

**Corpo da Requisição:**
- Formato: multipart/form-data
  - `file`: Arquivo da imagem do veículo.

**Resposta de Sucesso:**
- **Status:** 201 Created

---

## Rotas do Usuário Comum

### Criação do usuário
**POST** `/api/user/create`

**Descrição:** Cria um novo usuário no sistema.

**Corpo da Requisição:**
- Formato: JSON
  - `nome`: string
  - `nome_mat`:string
  - `email`: string
  - `login`: string
  - `senha`: string
  - `dataNascimento`: string (YYYY-MM-DD)
  - `genero`: ENUM('F', 'M', 'O')
  - `cpf`: string
  - `celular`: string

**Resposta de Sucesso:**
- **Status:** 201 Created
- **Corpo:** Dados do usuário criado (formato JSON)

### Login e autorização do usuário
**POST** `/api/user/login`

**Descrição:** Realiza o login e autoriza o usuário.

**Corpo da Requisição:**
- Formato: JSON
  - `login`: string || `email`: string
  - `senha`: string

**Resposta de Sucesso:**
- **Status:** 200 OK

### Rota de 2FA do usuário
**POST** `/api/user/2FA/{id}`

**Descrição:** Realiza a autenticação de dois fatores para o usuário.

**Parâmetros de URL:**
- `id` (integer): ID do usuário.

**Corpo da Requisição:**
- Token de autenticação JWT (formato JSON)

**Resposta de Sucesso:**
- **Status:** 200 OK

### Lista todas as informações do usuário
**GET** `/api/user/{id}`

**Headers:** Authorization: Bearer token: string 

**Descrição:** Retorna todas as informações de um usuário específico.

**Parâmetros de URL:**
- `id` (integer): ID do usuário.

**Resposta de Sucesso:**
- **Status:** 200 OK
- **Corpo:** Dados completos do usuário (formato JSON)

### Alteração de usuário
**PUT** `/api/user/update/{id}`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Atualiza as informações de um usuário específico.

**Parâmetros de URL:**
- `id` (integer): ID do usuário.

**Corpo da Requisição:**
- Formato: JSON
  - `nome` (opcional): string
  - `email` (opcional): string
  - `senha` (opcional): string
  - `dataNascimento` (opcional): string (YYYY-MM-DD)

**Resposta de Sucesso:**
- **Status:** 200 OK

### Exclusão de usuário
**DELETE** `/api/user/delete/{id}`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Exclui um usuário específico.

**Parâmetros de URL:**
- `id` (integer): ID do usuário.

**Resposta de Sucesso:**
- **Status:** 200 OK

### Upload da foto de perfil do usuário
**POST** `/api/upload/user/{id}`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Faz o upload da foto de perfil de um usuário específico.

**Parâmetros de URL:**
- `id` (integer): ID do usuário.

**Corpo da Requisição:**
- Formato: multipart/form-data
  - `file`: Arquivo da foto de perfil.

**Resposta de Sucesso:**
- **Status:** 201 Created

---

## Rotas do Log

### Listar todos os logs do sistema
**GET** `/api/admin/log`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Retorna uma lista com todos os logs do sistema.

**Resposta de Sucesso:**
- **Status:** 200 OK
- **Corpo:** Lista de logs (formato JSON)

### Criar um registro na tabela de log
**POST** `/api/admin/log`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Cria um registro na tabela de log ao autenticar um usuário.

**Corpo da Requisição:**
- Formato: JSON
  - `pickupDate`: datetime (YYYY-MM-DDTHH:HH)
  - `returnDate`: datetime (YYYY-MM-DDTHH:HH)
  - `id_vehicle`: number

**Resposta de Sucesso:**
- **Status:** 201 Created

---

## Rotas de Reserva

### Calcular o valor da reserva
**POST** `/api/booking/price`

**Descrição:** Calcula o valor da reserva e retorna o valor total.

**Resposta de Sucesso:**
- **Status:** 200 OK
- **Corpo:**
  - {
	`interval_days`: number,
	`total_price`: number,
	`vehicle`: {
		`id_vehicle`: number,
		`img`: string,
		`modelo`: string,
		`marca`: string,
		`categoria`: string,
		`ano`: year,
		`status`: boolean( 0 || 1)
		`preco`: float
	}
}

### Agendar uma nova reserva
**POST** `/api/booking/create`

**Headers:** `Authorization: Bearer token`: string 

**Descrição:** Cria uma nova reserva no sistema.

**Corpo da Requisição:**
- Formato: JSON
  - `id_user`: number
  - `id_vehicle`: number
  - `pickupDate`: datetime (YYYY-MM-DDTHH:HH)
  - `returnDate`: datetime (YYYY-MM-DDTHH:HH)
  - `location`: string

**Resposta de Sucesso:**
- **Status:** 201 Created
- **Corpo:** Dados da reserva criada (formato JSON)
