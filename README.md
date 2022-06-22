# Test project

## Install project

You need the docker engine and docker-compose, if you already have the docker engine and the docker-compose installed please update it !

The following ports are used by our containers:
* 127.0.0.1:8080 => PhpMyAdmin (login:root password: N/A)
* 127.0.0.1:8741 => Symfony/API
* 127.0.0.1:8081 => Mailer

### Docker

#### Build containers

```
docker-compose up -d
```

### Symfony containers

connect to symfony container:

```
docker exec -it www_docker_symfony bash
```

run composer

```
composer install
```

### Fixtures

Load fixtures:

```
// Create database db_test
php bin/console --env=dev doctrine:database:create

// Create table/column
php bin/console --env=dev doctrine:schema:create 

// Load fixtures in test env
php bin/console doctrine:fixtures:load
```

### Command retrieve expired products pending 

```
php bin/console notif:pending-product
```

### Test

Create database test env

```
// Create database db_test_test
php bin/console --env=test doctrine:database:create
    
// Create table/column
php bin/console --env=test doctrine:schema:create    

// Load fixtures in test env
php bin/console doctrine:fixtures:load --env=test
```

Run unit test   

```
php vendor/bin/phpunit
```

### Example Postman requests

You must be logged in first

###Login

```shell
curl --location --request POST '127.0.0.1:8741/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
"username": "admin@admin.com",
"password": "0000"
}'
```

```shell
{
    "user": "admin@admin.com",
    "role": [
        "ROLE_USER"
    ]
}
```


###Add Customer

```shell
curl --location --request POST '127.0.0.1:8741/customers' \
--header 'Content-Type: application/json' \
--data-raw '    {
        "firstName": "Dark",
        "lastName": "Vador",
        "dateOfBirth": "1980-06-19",
        "products": [
            // products ids
            "730",
            "870"
        ]
    }'
```
```shell
{
    "content": {
        "uuid": "1ecf1d98-fc85-69ee-ad56-4b840371f4c8",
        "firstName": null,
        "lastName": null,
        "dateOfBirth": null,
        "status": "new",
        "products": [],
        "createdAt": "2022-06-22T03:15:26+00:00",
        "updatedAt": null,
        "deletedAt": null
    }
}
```

Add Product
```shell
curl --location --request POST '127.0.0.1:8741/products' \
--header 'Content-Type: application/json' \
--data-raw '    {
        "issn": "RG45GRH",
        "name": "Apple",
        "status": "fezf",
        "customer": "1ecf1d98-fc85-69ee-ad56-4b840371f4c8"
    }'
```
```shell
{
    "content": {
        "id": 361,
        "issn": "RG45GRH",
        "name": "Apple",
        "status": "new",
        "customer": {
            "uuid": "1ecf1d98-fc85-69ee-ad56-4b840371f4c8",
            "firstName": null,
            "lastName": null,
            "dateOfBirth": null,
            "status": "new",
            "createdAt": "2022-06-22T03:15:26+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "2022-06-22T03:17:31+00:00",
        "updatedAt": null,
        "deletedAt": null
    }
}
```

Get Products

```shell
curl --location --request GET '127.0.0.1:8741/products' 
```
```shell
[
    {
        "id": 1,
        "issn": "k2u1-3j3w",
        "name": "Lesch, Hartmann and Kuvalis",
        "status": "pending",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "1940-02-26T09:35:15+00:00",
        "updatedAt": null,
        "deletedAt": null
    },
    {
        "id": 2,
        "issn": "j2e8-4k5i",
        "name": "Mitchell, Barton and Simonis",
        "status": "new",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "1964-04-03T13:36:56+00:00",
        "updatedAt": null,
        "deletedAt": null
    },
    {
        "id": 3,
        "issn": "i8s0-1j0r",
        "name": "McKenzie-Murazik",
        "status": "in review",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "1963-05-28T21:43:02+00:00",
        "updatedAt": null,
        "deletedAt": null
    },
    {
        "id": 4,
        "issn": "g6d4-9z8d",
        "name": "Herman, Jacobs and Murphy",
        "status": "deleted",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "2001-03-26T11:59:27+00:00",
        "updatedAt": null,
        "deletedAt": null
    },
    {
        "id": 5,
        "issn": "s9l3-6p0m",
        "name": "Mertz-Osinski",
        "status": "approved",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "1974-12-04T14:10:48+00:00",
        "updatedAt": null,
        "deletedAt": null
    },
    {
        "id": 6,
        "issn": "l2i0-0a5k",
        "name": "Osinski, Lynch and Reichert",
        "status": "deleted",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
        "createdAt": "1968-08-14T14:40:09+00:00",
        "updatedAt": null,
        "deletedAt": "2021-01-12T11:20:11+00:00"
    },
    {
        "id": 7,
        "issn": "f4j2-7t9e",
        "name": "Bergstrom, Bauch and Wiza",
        "status": "pending",
        "customer": {
            "uuid": "1ecf1d7d-b3e6-6ff0-aa80-1de825c4385a",
            "firstName": "Creola",
            "lastName": "Murray",
            "dateOfBirth": "1947-05-28T00:00:00+00:00",
            "status": "pending",
            "createdAt": "2020-12-06T08:34:01+00:00",
            "updatedAt": null,
            "deletedAt": null
        },
]
```