<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile;


/**
 * Interface BuilderInterface
 * @package ECG\DockerBuilder\Templates\PhpDockerFile
 */
interface BuilderInterface
{
    /**
     * add part to file
     * @param ContentPartInterface $part
     * @return mixed
     */
    public function addPart(ContentPartInterface $part);
    
    /**
     * build content of file
     * @return void
     */
    public function build();
}