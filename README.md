# Travel Order

## Considerações

- **Mailtip** foi utilizado para simular o envio de notificações por e-mail. Pode ser acessado localmente no endereço: [http://localhost:8025/](http://localhost:8025/).
  
- Foi criado um **serviço** para verificar se um pedido de viagem pode ser cancelado, incluindo as validações que julguei coerentes para essa operação.

---

## 🚀 Instruções para Iniciar o Projeto

Siga os passos abaixo para configurar o projeto:

1. **Clone o repositório** para o diretório de sua preferência:

   `git clone https://github.com/julesjrenck/travel_order.git`

2. **Acesse o diretório do projeto**:

   `cd travel_order`

3. **Instale as dependências do Laravel** usando o Composer:

   `composer install`

4. **Copie o arquivo `.env.example` para `.env`**:

   `cp .env.example .env`

5. **Crie e inicie os containers Docker** com o comando:

   `docker-compose up -d`

6. **Execute as migrations** para criar as tabelas no banco de dados:

   `docker-compose exec app php artisan migrate`

---

## Testes

# Por Comando

Para rodar os testes via terminal, execute o seguinte comando: 

`docker-compose exec app php artisan test`

# Por Endpoint

Rota: /register

Método: POST

Descrição: Registra um novo usuário no sistema e retorna um token.

Payload de Exemplo: { "name": "John Doe", "email": "user@example.com", "password": "password123", "password_confirmation": "password123" }

---

Rota: /login

Método: POST

Descrição: Autentica o usuário e retorna um token JWT.

Payload de Exemplo: { "email": "user@example.com", "password": "password123" }

---

# As rotas abaixo precisam do token


Rota: /travel-orders

Método: POST

Descrição: Cria um novo pedido de viagem para o usuário autenticado.

Payload de Exemplo: { "destination": "Paris", "start_date": "2025-01-01", "end_date": "2025-01-10" }

---

Rota: /travel-orders

Método: GET

Descrição: Retorna uma lista de pedidos de viagem filtrados pelo usuário autenticado.

Query Parameters (opcional):

status: Filtra pelo status do pedido (ex.: solicitado, aprovado, etc.).

start_date e end_date: Filtra por intervalo de datas.

destination: Filtra pelo destino.

---

Rota: /travel-orders/{id}

Método: GET

Descrição: Retorna os detalhes de um pedido de viagem específico.

---

Rota: /travel-orders/{id}

Método: PUT ou PATCH

Descrição: Atualiza o status de um pedido de viagem.

Payload de Exemplo: { "status": "aprovado" }


