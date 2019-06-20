# API Eventa - Tutorial para execução

## Dependências

Faça o download do **PHP 7.x** disponível em https://windows.php.net/download/ e descompacte o conteúdo do download.

Faça o download do **composer** disponível em https://getcomposer.org/download/. Siga as instruções de instalação. Durante a instalação será necessário apontar o diretório onde o PHP foi descompactado.

Faça o download do **MariaDB** disponível em https://mariadb.org/download/.

## Executar o projeto

O primeiro passo é criar um banco de dados com os mesmos dados encontrados no arquivo `.env` na raiz deste projeto. Caso ache necessário, é possível alterar os dados de acesso no arquivo. Por padrão é:
```env
Nome do banco: eventmanagerufpr
Usuário: root
Senha: root
```

O segundo passo é importar o banco de dados. Para isso use uma ferramenta como **MySQL Workbench** ou **Heidi SQL**. A estrutura do banco está disponível no arquivo *dump.sql* na raiz deste projeto.

Com o banco e as tabelas criadas, é hora de instalar as dependências do projeto. Para isso rode o comando abaixo no terminal.
>composer install

Depois que instalar as dependências rode o comando abaixo para iniciar o servidor no endereço **http://localhost:8000**.
>php artisan serve

Se tudo estiver correto você verá uma mensagem no terminal informando que o servidor está sendo executado.