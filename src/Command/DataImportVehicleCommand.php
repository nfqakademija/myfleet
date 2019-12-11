<?php

namespace App\Command;

use App\Service\VehicleDataImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataImportVehicleCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'data:import:vehicle';

    /**
     * @var VehicleDataImport
     */
    private $service;

    /**
     * DataImportVehicleCommand constructor.
     * @param VehicleDataImport $service
     */
    public function __construct(VehicleDataImport $service)
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
