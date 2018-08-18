#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use ECG\DockerBuilder\DefaultCommand;
use Symfony\Component\Console\Application;

define('DIRPATH', __DIR__ .'/');

$application = new Application('echo', '1.0.0');
$command = new DefaultCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);
$application->run();