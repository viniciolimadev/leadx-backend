# LeadX Backend

API REST em Symfony 7 com PHP 8.3, PostgreSQL, JWT e Docker.

## Stack

- **PHP** 8.3 (FPM Alpine)
- **Symfony** 7.2
- **PostgreSQL** 16
- **Nginx** 1.27
- **JWT** via `lexik/jwt-authentication-bundle`
- **CORS** via `nelmio/cors-bundle`

## Requisitos

- Docker e Docker Compose

## Configuração

1. Clone o repositório e acesse o diretório:
   ```bash
   git clone <repo> leadx-backend
   cd leadx-backend
   ```

2. Copie e ajuste as variáveis de ambiente:
   ```bash
   cp .env .env.local
   # Edite .env.local com suas configurações
   ```

3. Suba os containers (instala dependências, gera JWT keys e roda migrations automaticamente):
   ```bash
   make build
   ```

A API estará disponível em `http://localhost:8080`.

## Comandos úteis

| Comando | Descrição |
|---|---|
| `make start` | Inicia os containers |
| `make stop` | Para os containers |
| `make build` | Build e inicia os containers |
| `make shell` | Acessa o shell do container PHP |
| `make migrate` | Roda as migrations |
| `make migrations` | Gera uma nova migration |
| `make console CMD="..."` | Roda comando Symfony |
| `make composer CMD="..."` | Roda comando Composer |
| `make jwt-keys` | Regenera as chaves JWT |
| `make logs` | Exibe logs dos containers |
| `make test` | Roda os testes |

## Endpoints

### Autenticação

| Método | Endpoint | Descrição | Auth |
|---|---|---|---|
| `POST` | `/api/auth/register` | Cadastrar usuário | Não |
| `POST` | `/api/auth/login` | Login (retorna JWT) | Não |

### Usuário

| Método | Endpoint | Descrição | Auth |
|---|---|---|---|
| `GET` | `/api/me` | Dados do usuário autenticado | JWT |

### Exemplos

**Registrar:**
```bash
curl -X POST http://localhost:8080/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "senha123"}'
```

**Login:**
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "senha123"}'
```

**Rota protegida:**
```bash
curl http://localhost:8080/api/me \
  -H "Authorization: Bearer <seu_token_jwt>"
```

## Criar nova migration após alterar entidades

```bash
make migrations   # gera o arquivo de migration
make migrate      # aplica no banco
```
