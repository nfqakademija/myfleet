<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\VehicleDataImportService;

class DataImportVehicleCommand extends Command
{
    protected static $defaultName = 'data:import:vehicle';

    private $service;

    public function __construct(VehicleDataImportService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Imports fresh data from Vehicle API')
            ->setHelp('This command handles data import from Vehicle API')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Trying to import and update VehicleDataEntry');
        $this->service->importAndUpdateVehicleDataEntry();
        $output->writeln('Completed!');
    }
}
