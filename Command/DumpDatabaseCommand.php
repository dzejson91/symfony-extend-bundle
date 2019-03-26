<?php

/**
 * @author Krystian Jasnos <dzejson91@gmail.com>
 */

namespace JasonMx\ExtendBundle\Command;

use Doctrine\DBAL\Connection;
use Ifsnop\Mysqldump\Mysqldump;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DumpDatabaseCommand
 */
class DumpDatabaseCommand extends ContainerAwareCommand
{
    /** @var SymfonyStyle */
    private $io;

    protected function configure()
    {
        $this
            ->setName('doctrine:database:export')
            ->setDescription('Export database to SQL file')
            ->addOption(
                'file',
                null,
                InputOption::VALUE_OPTIONAL,
                'SQL file name', sprintf('backup-db-%s.sql', date('YmdHis'))
            )
            ->setHelp(<<<EOF
The <info>%command.name%</info> dump database into SQL file.
<info>php %command.full_name% --file=backup.sql</info>

EOF
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        @set_time_limit(0);
        $this->io = new SymfonyStyle($input, $output);

        $path = realpath($this->getContainer()->getParameter('kernel.root_dir')) . '/backup/';
        $fileName = $path . $input->getOption('file');

        /** @var Connection $db */
        $db = $this->getContainer()->get('doctrine.dbal.default_connection');
        $dbParams = $db->getParams();

        $dumper = new Mysqldump(
            sprintf('mysql:host=%s;port=%s;dbname=%s',
                $db->getHost(),
                $db->getPort(),
                $db->getDatabase()
            ),
            $db->getUsername(),
            $db->getPassword(),
            array(
                'compress' => Mysqldump::NONE,
                'net_buffer_length' => 4096,
                'add-drop-table' => true,
            ),
            $dbParams['driverOptions']
        );

        $dumper->start($fileName);

        if(is_file($fileName)){
            $output->writeln(sprintf('File created: %s', $fileName));
        } else {
            $output->writeln('Error file create!');
        }
    }
}
