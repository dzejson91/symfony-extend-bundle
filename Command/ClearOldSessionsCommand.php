<?php

/**
 * @author Krystian Jasnos <dzejson91@gmail.com>
 */

namespace JasonMx\ExtendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

/**
 * Class ClearOldSessionsCommand
 */
class ClearOldSessionsCommand extends ContainerAwareCommand
{
    /** @var SymfonyStyle */
    private $io;

    protected function configure()
    {
        $this
            ->setName('session:clear')
            ->setDescription('Clear old session files')
            ->addOption('days', null, InputOption::VALUE_OPTIONAL, 'Number of days.', 7)
            ->setHelp(<<<EOF
The <info>%command.name%</info> command clears all session files older then days number.

<info>php %command.full_name% --days=3</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $sessionPath = realpath($this->getContainer()->getParameter('session.save_path'));

        $finder = new Finder();
        $finder->files()->in($sessionPath)->date(sprintf('before %d days ago', $input->getOption('days')));

        if($finder->count()){
            $output->writeln('Deleting:');
            foreach ($finder as $file){
                $output->writeln(sprintf('- %s', $file->getFilename()));
                unlink($file->getRealPath());
            }
        } else {
            $output->writeln(sprintf('Not found files older then %d days', $input->getOption('days')));
        }
    }
}
