<?php


namespace ECG\DockerBuilder\Templates;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class NginxTemplates
 * @package ECG\DockerBuilder\Templates
 */
class NginxTemplates
{
    /**
     * Get .env templates
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getEnvTemplate(InputInterface $input) {
        if (empty($input->getOption('nginx_host')))
        {
            throw new \Exception('Set --nginx_host option to second argument nginx_container_name');
        }
        return "NGINX_HOST={$input->getOption('nginx_host')}
NGINX_PORT=80";
    }
    
    /**
     * return nginx conf
     * @param InputInterface $input
     * @return string
     */
    public function getConfTemplate (InputInterface $input) {
        $phpContainerName = strtolower($input->getArgument('php_container_name'));
        return "
#user  nginx;
worker_processes  1;

error_log  /var/log/nginx/error.log;
#error_log  /var/log/nginx/error.log  notice;
#error_log  /var/log/nginx/error.log  info;

#pid        /var/run/nginx.pid;

include /etc/nginx/modules.conf.d/*.conf;

events {
    worker_connections  1024;
}


http {
    include       mime.types;
    default_type  application/octet-stream;


    #access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  65;
    #tcp_nodelay        on;

    #gzip  on;
    #gzip_disable \"MSIE [1-6]\.(?!.*SV1)\";

    server_tokens off;

    include /etc/nginx/conf.d/*.conf;
	server {
    listen      80;
    listen      [::]:80;
    server_name    {$input->getOption('nginx_host')} www.{$input->getOption('nginx_host')};

    set \$MAGE_MODE developer;
    set \$MAGE_ROOT /var/www/html;
    root \$MAGE_ROOT/pub;

    index index.php;
    autoindex off;
    charset UTF-8;
    error_page 404 403 = /errors/404.php;
    #add_header \"X-UA-Compatible\" \"IE=Edge\";

    # PHP entry point for setup application
    location ~* ^/setup($|/) {
        root \$MAGE_ROOT;
        location ~ ^/setup/index.php {
            fastcgi_pass   $phpContainerName-php:9000;

            fastcgi_param  PHP_FLAG  \"session.auto_start=off \n suhosin.session.cryptua=off\";
            fastcgi_param  PHP_VALUE \"memory_limit=756M \n max_execution_time=600\";
            fastcgi_read_timeout 600s;
            fastcgi_connect_timeout 600s;

            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  \$document_root\$fastcgi_script_name;
            include        fastcgi_params;
        }

        location ~ ^/setup/(?!pub/). {
            deny all;
        }

        location ~ ^/setup/pub/ {
            add_header X-Frame-Options \"SAMEORIGIN\";
        }
    }

    # PHP entry point for update application
    location ~* ^/update($|/) {
        root \$MAGE_ROOT;

        location ~ ^/update/index.php {
            fastcgi_split_path_info ^(/update/index.php)(/.+)$;
            fastcgi_pass   $phpContainerName-php:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  \$document_root\$fastcgi_script_name;
            fastcgi_param  PATH_INFO        \$fastcgi_path_info;
            include        fastcgi_params;
        }

        # Deny everything but index.php
        location ~ ^/update/(?!pub/). {
            deny all;
        }

        location ~ ^/update/pub/ {
            add_header X-Frame-Options \"SAMEORIGIN\";
        }
    }

    location / {
        try_files \$uri \$uri/ /index.php\$is_args\$args;
    }

    location /pub/ {
        location ~ ^/pub/media/(downloadable|customer|import|theme_customization/.*\.xml) {
            deny all;
        }
        alias \$MAGE_ROOT/pub/;
        add_header X-Frame-Options \"SAMEORIGIN\";
    }

    location /static/ {
        # Uncomment the following line in production mode
        # expires max;

        # Remove signature of the static files that is used to overcome the browser cache
        location ~ ^/static/version {
            rewrite ^/static/(version[^/]+/)?(.*)$ /static/$2 last;
        }

        location ~* \.(ico|jpg|jpeg|png|gif|svg|js|css|swf|eot|ttf|otf|woff|woff2|json)$ {
            add_header Cache-Control \"public\";
            add_header X-Frame-Options \"SAMEORIGIN\";
            expires +1y;

            if (!-f \$request_filename) {
                rewrite ^/static/?(.*)$ /static.php?resource=$1 last;
            }
        }
        location ~* \.(zip|gz|gzip|bz2|csv|xml)$ {
            add_header Cache-Control \"no-store\";
            add_header X-Frame-Options \"SAMEORIGIN\";
            expires    off;

            if (!-f \$request_filename) {
               rewrite ^/static/?(.*)$ /static.php?resource=$1 last;
            }
        }
        if (!-f \$request_filename) {
            rewrite ^/static/?(.*)$ /static.php?resource=$1 last;
        }
        add_header X-Frame-Options \"SAMEORIGIN\";
    }

    location /media/ {
        try_files \$uri \$uri/ /get.php\$is_args\$args;

        location ~ ^/media/theme_customization/.*\.xml {
            deny all;
        }

        location ~* \.(ico|jpg|jpeg|png|gif|svg|js|css|swf|eot|ttf|otf|woff|woff2)$ {
            add_header Cache-Control \"public\";
            add_header X-Frame-Options \"SAMEORIGIN\";
            expires +1y;
            try_files \$uri \$uri/ /get.php\$is_args\$args;
        }
        location ~* \.(zip|gz|gzip|bz2|csv|xml)$ {
            add_header Cache-Control \"no-store\";
            add_header X-Frame-Options \"SAMEORIGIN\";
            expires    off;
            try_files \$uri \$uri/ /get.php\$is_args\$args;
        }
        add_header X-Frame-Options \"SAMEORIGIN\";
    }

    location /media/customer/ {
        deny all;
    }

    location /media/downloadable/ {
        deny all;
    }

    location /media/import/ {
        deny all;
    }

    # PHP entry point for main application
    location ~ (index|get|static|report|404|503|health_check)\.php$ {
        try_files \$uri =404;
        fastcgi_pass   $phpContainerName-php:9000;
        fastcgi_buffers 1024 4k;

        fastcgi_param  PHP_FLAG  \"session.auto_start=off \n suhosin.session.cryptua=off\";
        fastcgi_param  PHP_VALUE \"memory_limit=756M \n max_execution_time=18000\";
        fastcgi_read_timeout 600s;
        fastcgi_connect_timeout 600s;

        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  \$document_root\$fastcgi_script_name;
        include        fastcgi_params;
    }

    gzip on;
    gzip_disable \"msie6\";

    gzip_comp_level 6;
    gzip_min_length 1100;
    gzip_buffers 16 8k;
    gzip_proxied any;
    gzip_types
        text/plain
        text/css
        text/js
        text/xml
        text/javascript
        application/javascript
        application/x-javascript
        application/json
        application/xml
        application/xml+rss
        image/svg+xml;
    gzip_vary on;

    # Banned locations (only reached if the earlier PHP entry point regexes don't match)
    location ~* (\.php$|\.htaccess$|\.git) {
        deny all;
    }
}
	
}

# override global parameters e.g. worker_rlimit_nofile
include /etc/nginx/*global_params;
        ";
    }
    
    /**
     * return docker file
     * @return string
     */
    public function getDockerFileTemplate()
    {
       return 'FROM nginx
       
COPY ./configs/nginx.conf /etc/nginx/nginx.conf';
    }
}