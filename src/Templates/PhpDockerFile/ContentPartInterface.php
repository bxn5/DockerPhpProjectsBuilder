<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Interface ContentPartInterface
 * @package ECG\DockerBuilder\Templates\PhpDockerFile
 */
interface ContentPartInterface
{
    /**
     * getContent of Part
     * @param InputInterface $input
     * @return mixed
     */
   public function getContent(InputInterface $input);
}