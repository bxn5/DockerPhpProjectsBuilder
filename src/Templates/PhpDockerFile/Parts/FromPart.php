<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile\Parts;

use ECG\DockerBuilder\Templates\PhpDockerFile\ContentPartInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class FromPart
 * @package ECG\DockerBuilder\Templates\PhpDockerFile\Parts
 */
class FromPart implements ContentPartInterface
{
    /**
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getContent(InputInterface $input)
    {
        if (empty($input->getOption('php_version')))
        {
            throw new \Exception('Set --php_version option to third argument php_container_name');
        }
        return "FROM php:{$input->getOption('php_version')}-fpm";
    }
}