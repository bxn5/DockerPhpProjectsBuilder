<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile\Parts;

use ECG\DockerBuilder\Templates\PhpDockerFile\ContentPartInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ComposerPart
 * @package ECG\DockerBuilder\Templates\PhpDockerFile\Parts
 */
class ConfigsPart implements ContentPartInterface
{
    /**
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getContent(InputInterface $input)
    {
        return 'COPY configs/custom.ini /usr/local/etc/php/conf.d/custom.ini';
    }
}