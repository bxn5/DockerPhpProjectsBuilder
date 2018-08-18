<?php


namespace ECG\DockerBuilder\Templates\PhpDockerFile\Parts;

use ECG\DockerBuilder\Templates\PhpDockerFile\ContentPartInterface;
use Symfony\Component\Console\Input\InputInterface;

class XdebugPart implements ContentPartInterface
{
    /**
     * @param InputInterface $input
     * @return string
     * @throws \Exception
     */
    public function getContent(InputInterface $input)
    {
        if ($input->getOption('xdebug_enable') == true) {
            return 'RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini';
        } else {
            return '';
        }
    }
}