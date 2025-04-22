<?php

namespace App\Imports;

use App\Models\Marche;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarcheImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Marche([
            'reference' => $row['référence'],
            'realisation_envisagee' => $row['réalisation envisagée'],
            'type_marche' => $row['type de marché'],
            'source_financement' => $row['source financement'],
            'mode_passation' => $row['mode passation'],
            'date_lancement' => $row['date lancement'],
            'date_attribution' => $row['date attribution'],
            'date_demarrage' => $row['date démarrage'],
            'date_achevement' => $row['date achèvement'],
            'montant' => $row['montant'],
            'publicite' => $row['publicité'],
        ]);
    }
}
