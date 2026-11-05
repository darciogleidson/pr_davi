# Plano de execução — Diretório de empresas da igreja

## Objetivo

Construir um site público para encontrar empresas por nome, categoria e cidade. Um único responsável da igreja poderá manter os cadastros por uma área administrativa autenticada.

## Escopo inicial

- Visitantes consultam as empresas, mas não criam nem alteram cadastros.
- Há uma conta administrativa, sem cadastro público de usuários.
- Uma empresa pode pertencer a várias categorias, como veículos, hotéis, pousadas e depósitos.
- Empresas podem ser mantidas como rascunho ou publicadas.
- A busca pública filtra por nome, categoria e cidade e apresenta resultados paginados.
- A página de detalhes mostra contatos e redes sociais definidos para publicação.
- Instagram é opcional. O responsável informa o perfil e a quantidade de seguidores no cadastro.
- A quantidade de seguidores é um registro manual daquele momento, acompanhado da data e hora; não será consultada automaticamente no Instagram.
- O volume esperado é baixo e a aplicação utilizará uma única instância com SQLite.

## Tecnologias

- Docker Compose para o ambiente local.
- Laravel 13 para a aplicação.
- Livewire para busca dinâmica e telas administrativas, com lógica principalmente em PHP.
- SQLite em volume persistente do Docker.
- Tailwind CSS e Vite para os estilos e assets.

O `compose.yaml` atual configura o ambiente local de desenvolvimento. A publicação em produção terá configuração própria, com debug desativado e permissões de armazenamento restritas.

## Modelo de dados previsto

### Empresas

- Nome, slug, descrição e status de publicação.
- Telefone, WhatsApp, e-mail e site.
- Endereço, cidade e estado.
- Perfil do Instagram.
- Número de seguidores informado no cadastro e data/hora do registro.
- Logo opcional.
- Datas de criação e atualização.

### Categorias

- Nome, slug e situação ativa/inativa.
- Associação muitos-para-muitos com empresas.

### Usuário administrativo

- Nome, e-mail e senha com hash.
- A criação/atualização da conta será feita por comando Artisan interativo, sem credenciais padrão no repositório.

## Etapas

### 1. Base técnica

- Criar o esqueleto Laravel e instalar Livewire.
- Configurar PHP com suporte a SQLite no Docker.
- Configurar PHP-FPM, Nginx e Vite em Docker Compose.
- Persistir o banco SQLite e os uploads em volumes separados.
- Configurar idioma e fuso horário para português do Brasil.

### 2. Banco e cadastros

- Criar migrations, models, factories e seeders para empresas e categorias.
- Implementar os relacionamentos e índices necessários para filtros e slugs.
- Salvar `instagram_url`, `instagram_followers_at_registration` e `instagram_followers_recorded_at`.
- Validar URLs do Instagram e contagem como inteiro não negativo.
- Sem Instagram, os campos relacionados podem ficar vazios.

### 3. Acesso administrativo

- Implementar login, logout e limitação de tentativas.
- Desativar cadastro público de usuários.
- Proteger todas as rotas administrativas com autenticação.
- Fornecer comando seguro para criar ou atualizar o único usuário responsável.

### 4. Gestão de empresas e categorias

- Criar telas Livewire para listar, cadastrar, editar, publicar, ocultar e remover empresas.
- Permitir associar várias categorias à empresa.
- Registrar a contagem inicial de seguidores com data/hora e não alterá-la ao editar outros campos.
- Criar gestão das categorias pela área administrativa.

### 5. Diretório público

- Criar página inicial com busca por nome, categoria e cidade.
- Exibir apenas empresas publicadas, com paginação.
- Criar página de detalhes com contatos, perfil do Instagram e seguidores informados no cadastro.
- Adaptar a interface para celular e formatar a data da contagem em português.

### 6. Verificações

- Testar busca, filtros, paginação e ocultação de rascunhos.
- Testar autenticação, proteção das rotas e ausência de cadastro público.
- Testar criação/edição, categorias e validação dos dados de Instagram.
- Rodar testes e build de assets dentro ou por meio do ambiente Docker.

### 7. Publicação e operação

- Usar hospedagem que permita Docker Compose e armazenamento persistente.
- Configurar HTTPS, variáveis de ambiente e `APP_DEBUG=false` em produção.
- Manter uma única instância enquanto SQLite for utilizado.
- Configurar backup periódico do banco e dos uploads e testar a restauração.

## Critérios de conclusão do MVP

- O projeto inicia com `docker compose up --build`.
- O banco SQLite permanece após a recriação dos containers.
- O responsável consegue administrar empresas e categorias autenticado.
- O público consegue consultar empresas por nome, categoria e cidade.
- A quantidade de seguidores do Instagram aparece como um registro feito no cadastro, com a respectiva data.
- Empresas não publicadas não aparecem no diretório público.
- Os testes automatizados e o build de assets passam.

## Comandos para iniciar o ambiente local

```sh
docker-compose up --build
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan app:create-admin-user
docker-compose exec app php artisan test --compact
```

O ambiente publica a aplicação em `http://localhost:8000`. O Vite usa a porta `5174` no computador para não conflitar com outros projetos.

## Progresso da implementação

- [x] Base Laravel, Livewire, Docker Compose e SQLite persistente.
- [x] Migrations, models, factories e categorias iniciais.
- [x] Login do responsável, sem cadastro público, e comando para criar a conta administrativa.
- [x] Gestão administrativa de empresas e categorias.
- [x] Diretório público com busca por nome, categoria e cidade.
- [x] Perfil Instagram e seguidores registrados manualmente com data e hora.
- [x] Testes automatizados e build dos assets.
- [ ] Upload de logos, se for desejado para a primeira versão.
- [ ] Hospedagem de produção, backups automatizados e teste de restauração.
