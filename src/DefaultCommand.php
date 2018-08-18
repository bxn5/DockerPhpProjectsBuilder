<?php


namespace ECG\DockerBuilder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;


/**
 * Class DefaultCommand
 * @package ECG\DockerBuilder
 */
class DefaultCommand extends Command
{
    /**
     * @var FilesProcessor
     */
    private $fileProcessor;
    
    /**
     * @var Templates\ComposeTemplates
     */
    private $composeTemplate;
    
    /**
     * @var Templates\NginxTemplates
     */
    private $nginxTemplate;
    
    /**
     * @var Templates\PhpTemplates
     */
    private $phpTemplate;
    
    /**
     * @var Templates\MysqlTemplates
     */
    private $mysqlTemplate;
    
    /**
     * DefaultCommand constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->fileProcessor = new FilesProcessor();
        $this->composeTemplate = new Templates\ComposeTemplates();
        $this->nginxTemplate = new Templates\NginxTemplates();
        $this->phpTemplate = new Templates\PhpTemplates();
        $this->mysqlTemplate = new Templates\MysqlTemplates();
        
        parent::__construct($name);
    }
    
    /**
     * configure console commands
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to generate a docker project');
        
        $this->addArgument('projectRootFolder', InputArgument::REQUIRED, 'Your project root folder')
            ->addOption(
                'folder',
                null,
                InputOption::VALUE_REQUIRED,
                'Folder where project will be generated'
            );
        
        $this->addArgument('nginx_container_name', InputArgument::REQUIRED, 'Just name of nginx container')
            ->addOption(
                'nginx_port',
                null,
                InputOption::VALUE_REQUIRED,
                'expose nginx port'
                
            )
            ->addOption(
                'nginx_host',
                null,
                InputOption::VALUE_REQUIRED,
                'host name fro nginx config'
            );
        
        $this->addArgument('php_container_name', InputArgument::REQUIRED, 'just php container name')
            ->addOption(
                'php_version',
                null,
                InputOption::VALUE_REQUIRED,
                'version of php, support only fpm, list of available versions https://hub.docker.com/_/php/'
            )
            ->addOption(
                'xdebug_enable',
                null,
                InputOption::VALUE_REQUIRED,
                'enable xdebug, enabled by default',
                true
            );;
        
        $this->addArgument('mysql_container_name', InputArgument::REQUIRED, 'just mysql container name')
            ->addOption(
                'mariadb_version',
                null,
                InputOption::VALUE_REQUIRED,
                'version of mariadb, available versions https://hub.docker.com/_/mariadb/',
                '10.3')
            ->addOption(
                'mysql_user',
                null,
                InputOption::VALUE_REQUIRED,
                'mysql user')
            ->addOption(
                'mysql_root_password',
                null,
                InputOption::VALUE_REQUIRED,
                'root password')
            ->addOption(
                'mysql_password',
                null,
                InputOption::VALUE_REQUIRED,
                'user password')
            ->addOption(
                'mysql_database',
                null,
                InputOption::VALUE_REQUIRED,
                'database');
        
    }
    
    /**
     * execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        try {
            $this->fileProcessor->createStructure($input->getArgument('projectRootFolder'));
            $this->fileProcessor->convertToYamlFile($this->composeTemplate->getComposeTemplate($input));
            $this->createNginxFiles($input);
            $this->createPhpFiles($input);
            $this->createMysqlFiles($input);
            $output->writeln('--------');
            $output->writeln('--------');
            $output->writeln('<fg=green>SUCCESS</>');
            $output->writeln('--------');
            $output->writeln('--------');
            $output->writeln("Do not forget to add new host: <comment>{$input->getOption('nginx_host')}</comment> to your hosts file ");
            $output->writeln("After adding the host, you can run you container,
           <comment> cd {$input->getArgument('projectRootFolder')}</comment> to the directory with project
           and run <comment>docker-compose build</comment> and after successful building run <comment>docker-compose up -d </comment>");
            $output->writeln("If no errors occur you can open http://{$input->getOption('nginx_host')} in browser
            and be able to see output of phpinfo()");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            $this->fileProcessor->deleteRootFolder();
        }
        
    }
    
    /**
     * create files for nginx container
     * @param InputInterface $input
     * @throws \Exception
     */
    private function createNginxFiles(InputInterface $input)
    {
        $envTemplate = $this->nginxTemplate->getEnvTemplate($input);
        $this->fileProcessor->saveFile('nginx/configs/env-variables.env', $envTemplate);
        $nginxConf = $this->nginxTemplate->getConfTemplate($input);
        $this->fileProcessor->saveFile('nginx/configs/nginx.conf', $nginxConf);
        $dockerFileContent = $this->nginxTemplate->getDockerFileTemplate();
        $this->fileProcessor->saveFile('nginx/Dockerfile', $dockerFileContent);
        
        
    }
    
    /**
     * create files for php container
     * @param InputInterface $input
     */
    private function createPhpFiles(InputInterface $input)
    {
        $envTemplate = $this->phpTemplate->getEnvTemplate($input);
        $this->fileProcessor->saveFile('php/configs/env-variables.env', $envTemplate);
        $dockerFileContent = $this->phpTemplate->getDockerFileTemplate($input);
        $this->fileProcessor->saveFile('php/Dockerfile', $dockerFileContent);
        
    }
    
    /**
     * create file for mysql container
     * @param InputInterface $input
     * @throws \Exception
     */
    private function createMysqlFiles(InputInterface $input)
    {
        $envTemplate = $this->mysqlTemplate->getEnvTemplate($input);
        $this->fileProcessor->saveFile('mysql/configs/env-variables.env', $envTemplate);
        $dockerFileContent = $this->mysqlTemplate->getDockerFileTemplate($input);
        $this->fileProcessor->saveFile('mysql/Dockerfile', $dockerFileContent);
    }
    
}