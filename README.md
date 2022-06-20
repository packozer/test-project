# Test project

## Install project

You need the docker engine and docker-compose, if you already have the docker engine and the docker-compose installed please update it !

The following ports are used by our containers:
* 8080 => phpmyadmin
* 8741 => symfony
* 8081 => mailer

### Installing and Configuring the AWS CLI

#### Create your AWS CLI credentials

To do so, connect to greenflex AWS account at the [following url](https://newflex.signin.aws.amazon.com/console)

Create your ACCESS_KEY:
go to
https://console.aws.amazon.com/iam/home#/users/<my_login>?section=security_credentials`
and click on create your access key button

 NB: relpace <my_login> in the url by your login

#### Install the AWS Command Line Interface

   You can install the AWS Command Line Interface and its dependencies on most Linux distributions with pip, a package manager for Python.

   Follow these: [intructions](https://docs.aws.amazon.com/cli/latest/userguide/install-linux.html)

#### Configure the AWS CLI

Don't use the `aws configure` command but instead proceed to manually set up the AWS CLI.

- first, if required to, create the `.aws` folder at the root of your home.
- then create or open the `~/.aws/credentials` file and add your credentials as depicted here:
```
[newflex]
aws_access_key_id = XXXXXX
aws_secret_access_key = XXXXXX
```
- now create or open the ` ~/.aws/config` file and add any number of profiles you may require by adding blocks similar to the following one:
```
[profile greenflex-gfxiq-dev]
role_arn = arn:aws:iam::745588965159:role/gfx/gfxiq-dev-readonly-access
source_profile = newflex
```
[More detail](https://gitlab.greenflex.com/GreenflexIQ/services#user-content-use-the-aws-cli-with-profiles)

### Firstly Get the code !
#### If you have restricted access to iq
    git clone git@gitlab.greenflex.com:GreenflexIQ/docker.git iq
    cd iq
    make iq-clone

#### If you have full access on beegreen/iq
    git clone git@gitlab.greenflex.com:GreenflexIQ/docker.git iq
    cd iq
    make bg-clone
    make iq-clone

### Then you have to build containers
Please comment externalBilling service tha use php 7.3 (normally diag and weather will be commented too)
If you only need IQ

    cd iq
    make install

If you need IQ and Beegreen

    cd iq
    make install docker=bg

### Finally add hosts

Add the following hosts in `/etc/hosts`:

    127.0.0.1 beegreenbo.localhost.com myecoguide.localhost.com mytracking.localhost.com mypark.localhost.com myalert.localhost.com mybackoffice.localhost.com beegreen.localhost.com diagretail.localhost.com eveio.localhost.com myapi.localhost.com beegreenbo.localhost.com api.beegreen.localhost.com

connect to symfony container: 
    
    docker exec -it www_docker_symfony bash