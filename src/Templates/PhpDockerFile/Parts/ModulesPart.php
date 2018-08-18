<?php

namespace ECG\DockerBuilder\Templates\PhpDockerFile\Parts;

use ECG\DockerBuilder\Templates\PhpDockerFile\ContentPartInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ModulesPart
 * @package ECG\DockerBuilder\Templates\PhpDockerFile\Parts
 */
class ModulesPart implements ContentPartInterface
{
    /**
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getContent(InputInterface $input)
    {
        return 'RUN apt-get update && apt-get install -y \
    apt-utils \
    git \
    curl \
    unzip \
    libmcrypt-dev \
    libicu-dev \
    libxml2-dev libxslt1-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure hash --with-mhash \
    && docker-php-ext-install -j$(nproc) mcrypt intl xsl gd zip pdo_mysql opcache soap bcmath json iconv';
    }
}