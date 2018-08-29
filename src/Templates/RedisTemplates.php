<?php


namespace ECG\DockerBuilder\Templates;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class MysqlTemplates
 * @package ECG\DockerBuilder\Templates
 */
class RedisTemplates
{

    
    /**
     * get docker file
     * @param InputInterface $input
     * @return string
     */
    public function getDockerFileTemplate(InputInterface $input)
    {
        $redisVersion = 'latest';
        if (!empty($input->getOption('redis_version'))) {
            $redisVersion = $input->getOption('redis_version');
        }

        return "FROM redis:$redisVersion";
    }
}