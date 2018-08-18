<?php


namespace ECG\DockerBuilder\Templates;

use ECG\DockerBuilder\Templates\PhpDockerFile\Parts\FromPart;
use ECG\DockerBuilder\Templates\PhpDockerFile\Parts\ComposerPart;
use ECG\DockerBuilder\Templates\PhpDockerFile\Parts\XdebugPart;
use ECG\DockerBuilder\Templates\PhpDockerFile\PhpDockerFileBuilder;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class PhpTemplates
 * @package ECG\DockerBuilder\Templates
 */
class PhpTemplates
{
    /**
     * get env for php container
     * @param InputInterface $input
     * @return string
     */
    public function getEnvTemplate(InputInterface $input)
    {
        if ($input->getOption('xdebug_enable') == true) {
            return "XDEBUG_CONFIG=remote_host=172.17.0.1
PHP_IDE_CONFIG=serverName={$input->getOption('nginx_host')}";
        }
        else {
            return '';
        }
        
    }
    
    /**
     * build docker file for PHP container
     * @param InputInterface $input
     * @return string
     */
    public function getDockerFileTemplate(InputInterface $input) {
        $dockerFileBuilder = new PhpDockerFileBuilder($input);
        $dockerFileBuilder->addPart(new FromPart());
        $dockerFileBuilder->addPart(new ComposerPart());
        $dockerFileBuilder->addPart(new XdebugPart());
        return $dockerFileBuilder->build();
    }
}