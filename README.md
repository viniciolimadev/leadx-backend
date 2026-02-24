# LeadX Backend

API REST em Symfony 7 com PHP 8.3, PostgreSQL, JWT e Docker.

## Stack

- **PHP** 8.3 (FPM Alpine)
- **Symfony** 7.2
- **PostgreSQL** 16
- **Nginx** 1.27
- **JWT** via `lexik/jwt-authentication-bundle`
- **CORS** via `nelmio/cors-bundle`

```
┌─────────────────────────────────────────────┐
│                  Client                      │
└────────────────────┬────────────────────────┘
                     │ HTTP :8080
┌────────────────────▼────────────────────────┐
│              Nginx 1.27                      │  proxy reverso + arquivos estáticos
└────────────────────┬────────────────────────┘
                     │ FastCGI :9000
┌────────────────────▼────────────────────────┐
│            PHP 8.3-FPM                       │
│  ┌──────────────────────────────────────┐   │
│  │           Symfony 7.2                │   │  framework + DI + routing
│  │  ┌────────────┐  ┌────────────────┐  │   │
│  │  │  Security  │  │  Controllers   │  │   │  JWT auth + CORS
│  │  │  JWT/CORS  │  │  (business)    │  │   │
│  │  └────────────┘  └───────┬────────┘  │   │
│  │           Doctrine ORM   │           │   │  abstração do banco
│  └──────────────────────────┼───────────┘   │
└─────────────────────────────┼───────────────┘
                              │ SQL
┌─────────────────────────────▼───────────────┐
│            PostgreSQL 16                     │  dados persistentes
└─────────────────────────────────────────────┘
```

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

## Roles

Os perfis de acesso são gerenciados pela tabela `role` no banco de dados.

| Name |  Role | Descrição |
|---|---|---|
| `admin` | `ROLE_ADMIN` | Acesso total |
| `manager` | `ROLE_MANAGER` | Gestão de equipes |
| `seller` | `ROLE_SELLER` | Operações de venda |

As roles são populadas automaticamente via migration. Todo usuário recebe `ROLE_USER` por padrão, independente do perfil atribuído.

## Endpoints

### Autenticação

| Método | Endpoint | Descrição | Auth |
|---|---|---|---|
| `POST` | `/api/auth/register` | Cadastrar usuário (role padrão: seller) | Não |
| `POST` | `/api/auth/login` | Login — retorna token JWT | Não |

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

## Migrations

```bash
make migrations   # gera migration a partir das mudanças nas entidades
make migrate      # aplica as migrations pendentes no banco
```
