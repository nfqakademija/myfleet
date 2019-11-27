<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\RegistryDataImportService;

class DataImportRegistryCommand extends Command
{
    protected static $defaultName = 'data:import:registry';

    private $service;

    public function __construct(RegistryDataImportService $service)
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

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Trying to import and update RegistryDataEntry');
        $this->service->importAndUpdateRegistryDataEntry();
        $output->writeln('Completed!');
    }
}
