
# laravel-tabler

  

Criei esse repositório, porque não estou muito familiarizado com o tailwind que está vindo por padrão nas aplicações laravel.

  

Tabler é um kit de interface de usuário de aplicativo da web gratuito e de código aberto baseado no Bootstrap 5, com centenas de componentes responsivos e vários layouts.

  

## Requisitos

  

- PHP >= 8.2

- Composer

- Node.js

- npm

  

## Instalação

  

1. Clone o repositório:

```

$ git clone https://github.com/jord3v/laravel-tabler.git

```

  

2. Instale as dependências do Composer:

```

$ composer install

```

  

3. Instale as dependências do npm:

```

$ npm install

```

  

4. Copie o arquivo `.env.example` e renomeie para `.env`, depois configure as variáveis de ambiente conforme necessário.

  

5. Gere uma nova chave de aplicativo:

```

$ php artisan key:generate

```

  

6. Execute as migrações do banco de dados e insira dados iniciais:

```

$ php artisan migrate --seed

```

  

## Utilização

  

Para iniciar o servidor localmente:

```

$ php artisan serve

```

  

Acesse o seu aplicativo em [http://localhost:8000](http://localhost:8000).
|e-mail|senha|
|--|--|
|admin@admin.com|123456|

  

## Pacotes Utilizados

  

- [laravel/ui](https://github.com/laravel/ui)

- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)

- [tabler/tabler](https://github.com/tabler/tabler)

- [laravel-mix/laravel-mix](https://github.com/laravel-mix/laravel-mix)

  

## Contribuição

  

Se você quiser contribuir para este projeto, por favor abra uma issue para discutir as mudanças propostas.

  

## Licença

  

Este projeto é licenciado sob a [Licença MIT](https://opensource.org/license/mit).