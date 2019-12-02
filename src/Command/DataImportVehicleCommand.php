<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\VehicleDataImportService;

class DataImportVehicleCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'data:import:vehicle';

    /**
     * @var VehicleDataImportService
     */
    private $service;

    /**
     * DataImportVehicleCommand constructor.
     * @param VehicleDataImportService $service
     */
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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Trying to import and update VehicleDataEntry');
        $this->service->execute();
        $output->writeln('Completed!');
    }
}
