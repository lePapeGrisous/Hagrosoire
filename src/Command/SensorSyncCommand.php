<?php

namespace App\Command;

use App\Service\SensorApiService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sensor:sync',
    description: 'Synchronise les capteurs depuis l\'API externe',
)]
class SensorSyncCommand extends Command
{
    public function __construct(
        private SensorApiService $sensorApiService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronisation des capteurs');
        $io->text('Date: ' . (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

        $results = $this->sensorApiService->syncAllSensors();

        if (!empty($results['errors'])) {
            foreach ($results['errors'] as $error) {
                $io->error($error);
            }
        }

        if ($results['created'] === 0 && $results['updated'] === 0) {
            $io->warning('Aucun capteur synchronise');
            return Command::SUCCESS;
        }

        $io->success(sprintf(
            'Synchronisation terminee: %d capteur(s) cree(s), %d capteur(s) mis a jour',
            $results['created'],
            $results['updated']
        ));

        return Command::SUCCESS;
    }
}
