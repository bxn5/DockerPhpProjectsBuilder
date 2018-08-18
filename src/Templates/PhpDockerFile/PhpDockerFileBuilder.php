<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class PhpDockerFileBuilder
 * @package ECG\DockerBuilder\Templates\PhpDockerFile
 */
class PhpDockerFileBuilder implements BuilderInterface
{
    /**
     * accumulated array of parts
     * @var array
     */
    private $parts = [];
    /**
     * @var null|InputInterface
     */
    private $inputParams = null;
    
    /**
     * PhpDockerFileBuilder constructor.
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->inputParams = $input;
    }
    
    /**
     * add part
     * @param ContentPartInterface $part
     * @return mixed|void
     */
    public function addPart(ContentPartInterface $part)
    {
        $this->parts[] = $part;
    }
    
    /**
     * build file
     * @return string
     */
    public function build()
    {
        $result = '';
        foreach ($this->parts as $part) {
            $result .= $part->getContent($this->inputParams).PHP_EOL;
        }
        
        return $result;
    }
}