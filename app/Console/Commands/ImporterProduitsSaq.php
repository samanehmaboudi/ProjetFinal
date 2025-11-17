<?php

namespace App\Console\Commands;

use App\Services\SaqScraper;
use Illuminate\Console\Command;

class ImporterProduitsSaq extends Command
{
    protected $signature = 'saq:import 
                            {--categorie= : Catégorie spécifique à importer}
                            {--limite= : Nombre maximum de produits à importer}
                            {--delai=2 : Délai en secondes entre chaque requête}
                            {--client-id= : Client ID pour l\'API GraphQL Adobe}';

    protected $description = 'Importer les produits du catalogue SAQ';

    public function handle()
    {
        $this->info('Démarrage de l\'importation SAQ...');

        $categorie = $this->option('categorie');
        $limite = $this->option('limite') ? (int) $this->option('limite') : null;
        $delai = (int) $this->option('delai');
        $clientId = $this->option('client-id');

        if ($delai < 1) {
            $this->error('Le délai doit être d\'au moins 1 seconde');
            return Command::FAILURE;
        }

        try {
            $scraper = new SaqScraper($delai, $clientId);

            $this->info('Importation en cours...');
            if ($categorie) {
                $this->info("Catégorie: {$categorie}");
            }
            if ($limite) {
                $this->info("Limite: {$limite} produits");
            }
            $this->info("Délai entre requêtes: {$delai} secondes");

            $produitsImportes = $scraper->importerCatalogue($categorie, $limite, $delai);

            $this->info("Importation terminée. {$produitsImportes} produits importés.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'importation: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}

