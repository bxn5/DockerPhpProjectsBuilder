<?php


namespace ECG\DockerBuilder;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FilesProcessor
 * @package ECG\DockerBuilder
 */
class FilesProcessor
{
    /**
     * Symfony\Component\Filesystem
     * @var Filesystem
     */
    private $fileSystem;
    
    /**
     * root folder of project
     * @var null
     */
    private $rootDirName = null;
    
    /**
     * FilesProcessor constructor.
     */
    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }
    
    /**
     * create basic structure
     * @param $dirName
     * @throws \Exception
     */
    public function createStructure($dirName)
    {
        
        
        if (empty($dirName)) {
            throw new \Exception("Set root directory for the project, parameter --folder for first argument should be set");
        }
        $this->rootDirName = DIRPATH.$dirName;
        if ($this->fileSystem->exists($this->rootDirName)) {
            throw new \Exception("Project folder {$this->rootDirName} already exist");
        }
        $this->fileSystem->mkdir($this->rootDirName, 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/php/configs', 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/php/logs', 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/nginx/configs', 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/nginx/logs', 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/mysql/configs', 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/mysql/logs', 0755);
        $this->fileSystem->mkdir($this->rootDirName.'/sourceCode', 0755);
        $this->fileSystem->dumpFile($this->rootDirName.'/sourceCode/index.php', '<?php phpinfo(); ?>');
        
    }
    
    /**
     * delete folder in case of problems
     */
    public function deleteRootFolder()
    {
        $this->fileSystem->remove($this->rootDirName);
    }
    
    /**
     * convert array to yaml file
     * @param $dataArray
     */
    public function convertToYamlFile($dataArray)
    {
        $this->fileSystem->dumpFile($this->rootDirName.'/docker-compose.yaml', Yaml::dump($dataArray));
        
    }
    
    /**
     * @param $pathWithName
     * @param $content
     */
    public function saveFile($pathWithName, $content)
    {
        $this->fileSystem->dumpFile($this->rootDirName.'/'.$pathWithName, $content);
    }
}