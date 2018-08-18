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
    listen         80 default_server;
    listen         [::]:80 default_server;
    server_name    {$input->getOption('nginx_host')} www.{$input->getOption('nginx_host')};
    root           /var/www/html/;
    index          index.html;

        location / {
            index  index.php index.html index.htm;
        }

	  location ~* \.php\$ {
	    include         fastcgi_params;
	    fastcgi_param   SCRIPT_FILENAME    \$document_root\$fastcgi_script_name;
	    fastcgi_param   SCRIPT_NAME        \$fastcgi_script_name;
	    fastcgi_pass $phpContainerName-php:9000;
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