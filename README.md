# API  - Lumen PHP

Esta é uma API de testes feita com Lumen. Esta API serve para testar os recursos básicos de uma API, a persistência de dados é feita utilizando um banco de dados SQLite.

![Lumen PHP API](lumen.png)

Recursos disponíveis

* CRUD de livros
* Autenticação com JWT
* Envio de e-mail
* Upload de arquivos
* Swagger
* Testes unitários

## INSTALAÇÃO

> git clone https://github.com/dbins/lumen_api.git

> composer install


## CRIAR PROJETO

Para criar o projeto você pode primeiro adicionar o instalador do lumen. Se você já tiver o composer instalado, o comando é este
> composer global require "laravel/lumen-installer"

Depois, para criar o projeto:
> lumen new nome_do_projeto

Sem adicionar o instalador, o projeto pode ser criado da seguinte forma:
> composer create-project --prefer-dist laravel/lumen nome_do_projeto

Crie uma app_key utilizando o seguinte comando e depois atualize a chave APP_KEY do seu arquivo .env
>php -r "require 'vendor/autoload.php';use Illuminate\Support\Str;echo Str::random(40).PHP_EOL;"

Para testar, pelo terminal, entre dentro da pasta do projeto que foi criada e execute:
> php -S localhost:8000 -t public

A seguir, uma descrição de como foi feita a configuração de cada etapa

### Banco de dados SQLite

Dentro da pasta /database, crie um arquivo em branco chamado database.sqlite

Edite o arquivo .env e crie uma conexão com o banco de dados SQLite que acabou de ser criado

DB_CONNECTION=sqlite

Como o nome e caminho padrão são /database/database.sqlite, apenas a primeira linha é necessária

### Migrations

Agora vamos criar as migrations para o nosso banco de dados. Elas são responsáveis por criar a estrutura e os dados de nossobanco de dados. As migrations serão criadas dentro da pasta /database/migrations

>php artisan make:migration create_livros_table --create=livros

Para popular a tabela, será necessário criar um Seeder. Por padrão eles ficam dentro da pasta /database/seeds

> php artisan make:seeder LivrosTableSeeder

Depois de criar o seeder, atualize o arquivo /database/seeds/DatabaseSeeder.php, adicionando o Seeder para a tabela que foi criada

    public function run()
    {
        $this->call('LivrosTableSeeder');
    }
	
Para fazer a carga de dados, execute o comando:

> php artisan migrate --seed

 ### Rotas

Edite o arquivo /routes/web.php e crie as rotas do serviço

    $router->group([
        'prefix' => 'api/v1',]
        function () use ($router){
            $router->get('/livros', 'LivrosController@index');
            $router->get('/livros/{id}', 'LivrosController@show');
            $router->post('/livros', 'LivrosController@store');
            $router->patch('/livros/{id}', 'LivrosController@update');
            $router->delete('/livros/{id}', 'LivrosController@destroy');
    });

Será necessário criar um Controller, para isso crie um arquivo chamado LivrosController dentro da pasta /app/Http/Controllers

 ### ORM

 Para utilizar o ORM será necessário fazer as seguintes configurações:

No arquivo /bootstrap/app.php, descomente as seguintes linhas:

>$app->withEloquent();

>$app->withFacades();

Dentro da pasta /app, crie a pasta Models.
Dentro da pasta Models, crie um arquivo chamado Livro.php

### TESTES

Dentro da pasta /tests, crie um arquivo chamado LivrosTest.php. Você pode utilizar como base o conteúdo do arquivo ExampleTest.php
ATENÇÃO: Todos os testes devem seguir a seguinte regra: NomeControlleTest.php, caso contrário os testes não serão executados

Para executar os testes:

Saida em disco

>.\vendor\bin\phpunit --testdox-html resultado.html

>.\vendor\bin\phpunit --testdox-text resultado.txt

Saida em tela

>.\vendor\bin\phpunit --coverage-text

Obs: Os testes foram feitos no ambiente Windows.

### SWAGGER

O Swagger é uma ferramenta para documentação de APIs. Ela foi utilizada para documentar o controller.

Para instalar
> composer require zircote/swagger-php

Depois de documentar o Controller, para gerar a documentação você pode executar o comando:
> .\vendor\bin\openapi app -o public/swagger.json

A documentação é gerada no formato JSON. Com base nela, pode ser utilizada uma interface gráfica, para facilitar a consulta. Para instalar a interface gráfica, é necessário primeiro fazer o download deste repositório:

https://github.com/swagger-api/swagger-ui

Depois, copie a pasta "dist" para dentro de /public/swagger. Dentro de /public, você deverá criar esta pasta. Edite o arquivo index.html. alterando o caminho para chegar no arquivo /public/swagger.json

### CODESNIFFER

Foi instalada uma ferramenta para verificar o código em busca de erros ou melhorias.

Instalação

> composer require --dev "squizlabs/php_codesniffer 3.*"

Para testar o código e alertar sobre problemas:

> .\vendor\bin\phpcs app --standard=PSR12

Para testar o código e corrigir automaticamente:

> .\vendor\bin\phpcbf app --standard=PSR12

### AUTENTICAÇÃO

Para fins de autenticação, foi utilizado o JWT. Após fazer o login, será gerado um token. Este token será enviado no cabeçalho das requisições seguintes. (Bearer Token). Desta forma, será possível restringir o acesso a determinadas rotas

Primeiro, foi criada a tabela de users, com a sua respectiva migration e seed.

>php artisan make:migration create_users_table --create=users

>php artisan make:seeder UsersTableSeeder

O arquivo /database/seeds/DatabaseSeeder.php foi alterado, adicionando o novo seeder criado.
Foi criado um modelo para acessar o usuário por ORM em /app/Models/User.php
Por último, foi feita a migração dos dados para o banco

>php artisan migrate --seed

Para criar o token no formato JWT foi instalado o seguinte componente:

>composer require firebase/php-jwt

No arquivo .env, foi criado um parâmetro chamado JWT-SECRET, que vai guardar o segredo usado para assinar os tokens.

Foi criado o controller /app/Http/Controllers/AuthController.php
Foi criado o middleware /app/Http/Middleware/JwtMiddleware.php
criar middleware

Foi registrado este middleware no arquivo /bootstrap/app.php

    $app->routeMiddleware([
        'jwt.auth' => App\Http\Middleware\JwtMiddleware::class,
    ]);

Foi criada uma rota restrita em /Routes/web.php. A restrição do acesso é feita pelo middleware. O endereço é /users

    $router->group(['middleware' => 'jwt.auth'], function() use ($router) {
        $router->get('/users', function() {
            $users = \App\Models\User::all();
            return response()->json($users);
        });
    });

Para acessar uma rota restrita, é necessário enviar o Bearer token no header da requisição.
Para ler o token no middleware ou em qualquer outro lugar da aplicação
>$token = $request->bearerToken();

### EMAIL

Para o disparo de e-mails foi utilizado o seguinte componente
>composer require illuminate/mail

No arquivo .env foram inseridas as credenciais do servidor de e-mail, no exemplo a seguir os dados são do mailtrap.io

    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=82b8778d42a752
    MAIL_PASSWORD=66a035d7b3945a
    MAIL_ENCRYPTION=null

Registrar o provider no /bootstrap/app

    $app->register(Illuminate\Mail\MailServiceProvider::class);

Foi criado o arquivo de configuração/config/mail.php

Registrar os seguintes includes no /bootstrap/app

    $app->register(Illuminate\Mail\MailServiceProvider::class);
    $app->configure('mail');
    $app->alias('mailer', Illuminate\Mail\Mailer::class);
    $app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
    $app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);

Criar pasta e arquivo /app/Mail
Criar o controlador e a rota
Criar o layout do e-mail na pasta /resources/views/emails. Os layouts utilizam o Blade

### UPLOAD DE ARQUIVOS

Para o upload de arquivos, foi necessário instalar um componente para gravar o arquivo na pasta /public

>composer require illuminate/filesystem

>composer require league/flysystem

Depois, foi criado o arquivo /config/filesystems.php
E na sequência foi feito o registro do provider em /bootstrap/app.php

    $app->singleton('filesystem', function ($app) { return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem'); });

### MENSAGENS DE VALIDACAO EM PORTUGUES

Para traduzir para português do Brasil as mensagens de validação geradas pelo request-validate() foi necessário criar a pasta /resources/lang/pt_BR e copiar para esta pasta os arquivos com as validações traduzidas.

Depois, dentro do arquivo /bootstrap/app foi adicionada a seguinte linha depois de carregar os providers

> \Illuminate\Support\Facades\Lang::setLocale('pt_BR');

### LISTAR ROTAS DA APLICAÇÃO

Apesar do Lumen ser baseado no framework Laravel, ele não possui a funcionalidade de listar as rotas pelo terminal. Se faz necessário instalar um componente para poder ter acesso a este recurso.

>composer require thedevsaddam/lumen-route-list

>composer update

Adicionar em /bootstrap/app.php
    $app->register(\Thedevsaddam\LumenRouteList\LumenRouteListServiceProvider::class);

Agora estão disponíveis no terminal os seguintes comandos:
>php artisan route:list

Filtrar rotas que utilizem o método POST
>php artisan route:list --filter=method:post

### EXPORTAÇÃO DE DADOS

A API possui uma rota chamada /livros/exportar/{formato} que permite exportar os dados cadastrados em 3 formatos, CSV, Excel ou PDF. Os dados são devolvidos como uma string no formato base64.

Para exportar para CSV, como o formato é texto, não é necessário instalar nenhum componente. Para exportar para Excel, é necessário instalar o seguinte componente:

> composer require spatie/simple-excel

Para exportar para PDF é necessário instalar o seguinte componente:

> composer require barryvdh/laravel-dompdf

> composer update

Edite o arquivo /bootstrap/app.php e adicione o service provider.

	$app->register(\Barryvdh\DomPDF\ServiceProvider::class);


### ROTAS

| Method | URI                               | Name | Action     | Middleware | Map To                                           |
|--------|-----------------------------------|------|------------|------------|--------------------------------------------------|
| GET    | /api/v1/livros                    |      | Controller |            | App\Http\Controllers\LivrosController@index      |
| GET    | /api/v1/livros/{id}               |      | Controller |            | App\Http\Controllers\LivrosController@show       |
| POST   | /api/v1/livros                    |      | Controller |            | App\Http\Controllers\LivrosController@store      |
| PUT    | /api/v1/livros/{id}               |      | Controller |            | App\Http\Controllers\LivrosController@update     |
| DELETE | /api/v1/livros/{id}               |      | Controller |            | App\Http\Controllers\LivrosController@destroy    |
| POST   | /api/v1/livros/contato            |      | Controller |            | App\Http\Controllers\LivrosController@contact    |
| POST   | /api/v1/livros/upload/{id}        |      | Controller |            | App\Http\Controllers\LivrosController@upload     |
| GET    | /api/v1/livros/upload/{id}        |      | Controller |            | App\Http\Controllers\LivrosController@image      |
| POST   | /api/v1/auth/login                |      | Controller |            | App\Http\Controllers\AuthController@authenticate |
| GET    | /api/v1/livros/exportar/{formato} |      | Controller |            | App\Http\Controllers\LivrosController@export     |
| GET    | /users                            |      | Closure    | jwt.auth   |                                                  |
| GET    | /api/doc                          |      | Controller |            | App\Http\Controllers\LivrosController@docs       |

