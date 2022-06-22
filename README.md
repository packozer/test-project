# Test project

## Install project

You need the docker engine and docker-compose, if you already have the docker engine and the docker-compose installed please update it !

The following ports are used by our containers:
* 127.0.0.1:8080 => PhpMyAdmin
* 127.0.0.1:8741 => Symfony/API
* 127.0.0.1:8081 => Mailer

### Docker

#### Build containers

    docker-compose up-d

### Symfony containers
connect to symfony container:

    docker exec -it www_docker_symfony bash

### Fixtures

Load fixtures:

    php bin/console doctrine:fixtures:load

### Command retrieve expired products pending 

    php bin/console notif:pending-product

### Test

Create database test env

    // Create database db_test_test
    php bin/console --env=test doctrine:database:create
    
    // Create table/column
    php bin/console --env=test doctrine:schema:create    

    // Load fixtures in test env
    php bin/console doctrine:fixtures:load --env=test

### Example Postman requests

You must be logged in first

Login

    `Content-Type: application/json`
    //Path (METHOD: POST)
        127.0.0.1:8741/api/login
    
    // Body
    {
        "username": "admin@admin.com",
        "password": "0000"
    }

Add Customer

    //Path (METHOD: POST)
        127.0.0.1:8741/customers
    
    //Body
    {
        "firstName": "Dark",
        "lastName": "Vador",
        "dateOfBirth": "1980-06-19",
        "products": [
            // products ids
            "730",
            "870",
            ...
        ]
    }

Add Product

    //Path (METHOD: POST)
        127.0.0.1:8741/products
    
    //Body
    {
        "issn": "RG45GRH",
        "name": "Apple",
        "status": "fezf",
        "customer": "1ecf1317-b06b-6896-888c-2172a813df5b"
    }

Get Products

    //Path (METHOD: GET)
        127.0.0.1:8741/products
