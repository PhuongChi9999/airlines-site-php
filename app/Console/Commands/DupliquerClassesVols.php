<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Flight;

class DupliquerClassesVols extends Command
{
    protected $signature = 'vol:dupliquer-classes';

    protected $description = 'Dupliquer chaque vol avec les classes: Économique, Affaires, Première';

    public function handle()
    {
        $flights = Flight::all();
        $count = 0;

        foreach ($flights as $flight) {
            if ($flight->class !== 'Classe Économique') {
                continue;
            }

            // Classe Affaires
            $affaires = $flight->replicate();
            $affaires->class = 'Classe Affaires';
            $affaires->flight_number .= '-AFF';
            $affaires->price = $flight->price + 500;
            $affaires->available_seats = (int)($flight->available_seats * 0.6);
            $affaires->save();
            $count++;

            // Première Classe
            $premiere = $flight->replicate();
            $premiere->class = 'Première Classe';
            $premiere->flight_number .= '-PRE';
            $premiere->price = $flight->price + 1000;
            $premiere->available_seats = (int)($flight->available_seats * 0.3);
            $premiere->save();
            $count++;
        }

        $this->info("✅ {$count} vols dupliqués avec succès !");
    }
}
