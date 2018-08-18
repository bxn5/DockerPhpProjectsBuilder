<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile\Parts;

use ECG\DockerBuilder\Templates\PhpDockerFile\ContentPartInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ComposerPart
 * @package ECG\DockerBuilder\Templates\PhpDockerFile\Parts
 */
class ComposerPart implements ContentPartInterface
{
    /**
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getContent(InputInterface $input)
    {
        return 'COPY --from=composer:1.5 /usr/bin/composer /usr/bin/composer';
    }
}