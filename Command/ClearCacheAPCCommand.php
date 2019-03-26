<?php

/**
 * @author Krystian Jasnos <dzejson91@gmail.com>
 */

namespace JasonMx\ExtendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ClearCacheAPCCommand
 */
class ClearCacheAPCCommand extends ContainerAwareCommand
{
    /** @var SymfonyStyle */
    private $io;

    protected function configure()
    {
        $this
            ->setName('cache:clear:apc')
            ->setDescription('Clear cache: APC, APCu');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        if(function_exists('apc_clear_cache')){
            $output->writeln('APC cache clearing...');
            $result = apc_clear_cache();
            $output->writeln($result ? 'APC cache cleared' : 'Error clear APC');
        } else {
            $output->writeln('APC not found');
        }

        if(function_exists('apc_clear_cache')){
            $output->writeln('APCu cache clearing...');
            $result = apcu_clear_cache();
            $output->writeln($result ? 'APCu cache cleared' : 'Error clear APCu');
        } else {
            $output->writeln('APCu not found');
        }
    }
}
