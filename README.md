# Projeto Laravel com Docker

Este projeto é uma aplicação Laravel configurada para rodar com Docker. A seguir, você encontrará instruções detalhadas sobre como configurar, executar e utilizar o projeto.

## Pré-requisitos

- Docker
- Docker Compose

## Configuração

Antes de iniciar o projeto, configure as seguintes variáveis no arquivo `.env`:

```env
APP_PORT=
DB_USERNAME=
DB_PASSWORD=
```

### Exemplo de Configuração

```env
APP_PORT=8080
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

## Passos para Executar o Projeto

1. **Construir e iniciar os contêineres:**

   ```sh
   docker-compose up -d
   ```

   Isso irá construir as imagens Docker e iniciar os contêineres em segundo plano.

2. **Executar as migrações do banco de dados:**

   ```sh
   docker exec product php artisan migrate
   ```

## Ativando o Horizon

Para ativar o Horizon, certifique-se de que as seguintes variáveis estão configuradas corretamente no arquivo `.env`:

```env
QUEUE_CONNECTION=redis
REDIS_CLIENT=predis
REDIS_HOST=product-redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Em seguida, execute o comando:

```sh
docker exec -d product php artisan horizon
```

## Importação de Produtos

### Configuração da URL para Importação

Certifique-se de que a variável `COMMAND_PRODUCT_IMPORT_URL` está configurada corretamente no arquivo `.env`:

```env
COMMAND_PRODUCT_IMPORT_URL='https://fakestoreapi.com'
```

### Importação de Todos os Produtos

Para importar todos os produtos, execute o comando:

```sh
docker exec product php artisan products:import
```

### Importação de Produtos Específicos por ID

Para importar produtos específicos por ID, use o seguinte comando:

```sh
docker exec product php artisan products:import --id=1 --id=2 --id=3
```

Substitua `1`, `2`, `3` pelos IDs dos produtos que deseja importar.

## Rodando os Testes

Para rodar os testes, execute o comando:

```sh
docker-compose run --rm product php ./vendor/bin/pest
```

## Parando os Contêineres

Para parar os contêineres, execute:

```sh
docker-compose down
```

## Problemas Comuns

### Erro de Conexão com o Banco de Dados

Certifique-se de que as variáveis `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` estão configuradas corretamente no arquivo `.env`.

### Permissões de Arquivos

Se encontrar problemas de permissão, verifique se o usuário e grupo do contêiner têm acesso de leitura e escrita às pastas necessárias.

---

Com essas instruções, você deve conseguir configurar e rodar o projeto sem problemas. Se precisar de mais ajuda, consulte a [documentação oficial do Laravel](https://laravel.com/docs) e do [Docker](https://docs.docker.com/).