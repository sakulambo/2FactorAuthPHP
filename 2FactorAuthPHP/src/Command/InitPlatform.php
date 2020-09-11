<?php
/**
 * Created by IntelliJ IDEA.
 * User: Admin
 * Date: 04/12/2018
 * Time: 16:41
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitPlatform  extends Command
{

    protected static $defaultName = 'init:platform';
    protected function configure(){
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        system("php bin/console doctrine:database:create");
        system("php bin/console doctrine:schema:drop --force");
        system("php bin/console doctrine:schema:create");
        system("php bin/console doctrine:schema:update --force");
    }
}