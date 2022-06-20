# Test project

## Install project

You need the docker engine and docker-compose, if you already have the docker engine and the docker-compose installed please update it !

The following ports are used by our containers:
* 127.0.0.1:8080 => phpmyadmin
* 127.0.0.1:8741 => symfony
* 127.0.0.1:8081 => mailer

### Docker

#### Build containers

    docker-compose up-d


### Symfony containers
connect to symfony container:

    docker exec -it www_docker_symfony bash
### Fixtures

Load fixtures:

    php bin/console doctrine:fixtures:load

### User
add `Content-Type: application/json` in the header for the login path: /api/login

    {
        "username": "admin@admin.com",
        "password": "0000"
    }