# DockerPhpProjectsBuilder

This CLI tool gives a possibility to generate from scratch ready to use docker project with 
Nginx, PHP-FPM, and MariaDB 

## Getting Started

These instructions will get you a copy of the project up and be running on your local machine 

### Prerequisites

All required is php => 7 and composer

```
php-cli 
composer
```

### Installing


Clone project 



And install all dependencies

```
composer install
```

### Usage

```
php console.php projectRootFolder \
nginxContainerName --nginx_port=80 --nginx_host=myhost.loc \
phpContainerName --php_version=7.1 --xdebug_enable=true \
mysqlContainerName --mysql_user=mysqluser --mysql_root_password=pwd --mysql_password=ww --mysql_database=ffff --mariadb_version=10.3
/
```


### Generated Project structure

```
rootDir
    -- php
        --configs
            env-variables.env
        --logs
        Dockerfile
    -- nginx
        --configs
            env-variables.env
            nginx.conf
        --logs
        Dockerfile
    -- mysql
        --configs
            env-variables.env
        --logs
        Dockerfile
    docker-compose.yaml
    -- sourceCode
        index.php
```


### Versions of docker images 

Find proper version is possible on image official hub website

Nginx using container - https://hub.docker.com/_/nginx/

Php fpm - https://hub.docker.com/_/php/

Mariadb - https://hub.docker.com/_/mariadb/

For example, if you have problems with PHP version,
you can go to https://hub.docker.com/_/php/ and find the proper version 
with a suffix "-fpm" at the end


### Additional possibility

This tool generates env-variables.env files for each container and you can set some 
environment variables which provide by official containers, the list of available variables also can be found 
in image official hub website

### After generating

Do not forget to add the new host to your hosts file 

After adding the host, you can run your container,
            ``cd projectRootFolder`` to the directory with the project
           and run ``docker-compose build``
           
After successful building run 

``docker-compose up -d``
 
If no errors occur you can open http://yourhost in a browser
            and be able to see an output of phpinfo()
            
### Trivial tasks
CONTAINER - it's container name 

All commands you can run from host machine :
#### Composer
Composer available inside PHP container

``docker exec -it CONTAINER composer update``

#### Import Database

``cat backup.sql | docker exec -i CONTAINER /usr/bin/mysql -u root --password=root DATABASE``
