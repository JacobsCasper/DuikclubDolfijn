# Duikclubdolfijn

This is a site created for Duikclub Dolfijn Bilzen.

## Installation

This project uses Docker to run its code.

Use [Docker installation](https://www.docker.com/get-started).

Use your bash inside the project execute the following lines.

```shell
$ docker-compose up -d
$ docker exec -it duikclubdolfijn_php_1 bash

$ php bin/console doctrine:migrations:migrate
```

To unlock all functionality of this site you will need an administrator user.

To get this you need to execute the following line inside the docker php bash you accessed in the previous script.
```shell
$ php bin/console doctrine:fixtures:load
```

## Usage

>Open any browser.
>Surf to localhost:8000
>
>The site should open.
>
>You should be able to login as administrator using the following credentials. (Username: admin, password: admin)

