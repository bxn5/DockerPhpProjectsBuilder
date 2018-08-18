<?php


namespace ECG\DockerBuilder\Templates;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class MysqlTemplates
 * @package ECG\DockerBuilder\Templates
 */
class MysqlTemplates
{
    /**
     * get .env template
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getEnvTemplate(InputInterface $input)
    {
        if (empty($input->getOption('mysql_user')))
        {
            throw new \Exception('Set --mysql_user option to 4th argument mysql_container_name');
        }
        if (empty($input->getOption('mysql_root_password')))
        {
            throw new \Exception('Set --mysql_root_password option to 4th argument mysql_container_name');
        }
        if (empty($input->getOption('mysql_password')))
        {
            throw new \Exception('Set --mysql_password option to 4th argument mysql_container_name');
        }
        if (empty($input->getOption('mysql_database')))
        {
            throw new \Exception('Set --mysql_database option to to 4th argument mysql_container_name');
        }
        return "MYSQL_ROOT_PASSWORD={$input->getOption('mysql_root_password')}
MYSQL_DATABASE={$input->getOption('mysql_database')}
MYSQL_USER={$input->getOption('mysql_user')}
MYSQL_PASSWORD={$input->getOption('mysql_password')}";
    }
    
    /**
     * get docker file
     * @param InputInterface $input
     * @return string
     */
    public function getDockerFileTemplate(InputInterface $input)
    {
        return "FROM mariadb:{$input->getOption('mariadb_version')}";
    }
}