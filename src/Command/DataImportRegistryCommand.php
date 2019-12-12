<?php

namespace App\Command;

use App\Service\RegistryDataImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataImportRegistryCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'data:import:registry';

    /**
     * @var RegistryDataImport
     */
    private $service;

    /**
     * @param RegistryDataImport $service
     */
    public function __construct(RegistryDataImport $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Imports fresh data from Registry API')
            ->setHelp('This command handles data import from Registry API')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Trying to import and update RegistryDataEntry');
        $this->service->execute();
        $output->writeln('Completed!');
    }
}
