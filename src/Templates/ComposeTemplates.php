<?php


namespace ECG\DockerBuilder\Templates;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ComposerTemplates
 * @package ECG\DockerBuilder\Templates
 */
class ComposeTemplates
{
    /**
     * return docker-compose template
     * @param InputInterface $input
     * @return array
     * @throws \Exception
     */
    public function getComposeTemplate(InputInterface $input)
    {
       if (empty($input->getOption('nginx_port')))
       {
           throw new \Exception('Set --nginx_port option to second argument nginx_container_name');
       }
      return array (
          'version' => '3',
          'volumes' =>
              array (
                  'db-data' => NULL,
              ),
            'services' => array (
                strtolower ($input->getArgument('php_container_name')).'-php' =>
                        array (
                            'env_file' =>
                                array (
                                    0 => "./php/configs/env-variables.env",
                                ),
                            'build' => "php",
                            'volumes' =>
                                array (
                                     './sourceCode:/var/www/html',
                                     './php/logs:/var/log/'
                                ),
                        ),
                strtolower ($input->getArgument('mysql_container_name')).'-db' =>
                        array (
                            'env_file' =>
                                array (
                                    0 => "./mysql/configs/env-variables.env",
                                ),
                            'build' => "./mysql",
                            'volumes' =>
                                array (
                                    0 => 'db-data:/var/lib/mysql',
                                    './mysql/logs:/var/log'
                                ),
                        ),
                strtolower ($input->getArgument('nginx_container_name')). '-nginx' =>
                        array (
                            'env_file' =>
                                array (
                                    0 => "./nginx/configs/env-variables.env",
                                ),
                            'command' =>
                                array (
                                    0 => 'nginx-debug',
                                    1 => '-g',
                                    2 => 'daemon off;',
                                ),
                            'build' => "./nginx",
                            'volumes' =>
                                array (
                                    0 => './sourceCode:/var/www/html',
                                    './nginx/logs:/var/log/nginx'
                                ),
                            'ports' =>
                                array (
                                    0 => $input->getOption('nginx_port').':80',
                                ),
                        ),
                ),
          
        );
     

    }
}