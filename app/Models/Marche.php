<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class Marche extends Model
{
    use Importable;
    protected $fillable = [
        'reference',
        'realisation_envisagee',
        'type_marche',
        'source_financement',
        'mode_passation',
        'date_lancement',
        'date_attribution',
        'date_demarrage',
        'date_achevement',
        'montant',
        'publicite',
        'created_at',
        'updated_at',
    ];

    public function model(array $row)
    {
        return new Marche([
            'reference' => $row[0],
            'realisation_envisagee' => $row[1],
            'type_marche' => $row[2],
            'source_financement' => $row[3],
            'mode_passation' => $row[4],
            'date_lancement' => $row[5],
            'date_attribution' => $row[6],
            'date_demarrage' => $row[7],
            'date_achevement' => $row[8],
            'montant' => $row[9],
            'publicite' => $row[10],
        ]);
    }
}
