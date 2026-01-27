<?php

namespace App\Command;

use App\Service\HydroliqueService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:hydrolique:calculate',
    description: 'Execute l\'algorithme de decision d\'arrosage pour toutes les zones',
)]
class HydroliqueCalculateCommand extends Command
{
    public function __construct(
        private HydroliqueService $hydroliqueService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Calcul des bilans hydroliques');
        $io->text('Date: ' . (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

        $results = $this->hydroliqueService->calculateAllZones();

        if (empty($results)) {
            $io->warning('Aucun nouveau bilan cree (bilans deja existants pour aujourd\'hui ou aucune zone)');
            return Command::SUCCESS;
        }

        $io->success(sprintf('%d bilan(s) cree(s)', count($results)));

        $io->table(
            ['Zone', 'Decision', 'Volume (L)', 'Stock'],
            array_map(fn($r) => [
                $r['zone'],
                $r['decision'],
                $r['volume'],
                $r['stock'],
            ], $results)
        );

        return Command::SUCCESS;
    }
}
