<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandeAvis extends Model
{
    use HasFactory;

    // protected $table = 'demandes_avis';

    // protected $fillable = [
    //     'marche_id',
    //     'created_by',
    //     'assigned_to',
    //     'statut',
    //     'document',
    //     'projet_avis',
    //     'avis_final',
    // ];

    // protected $fillable = [
    //     'marche_id',
    //     'created_by',
    //     'document',
    //     'commentaire',
    // ];

    protected $fillable = [
        'marche_id',
        'created_by',
        'assigned_to',
        'statut',
        'document',
        'commentaire',
    ];
    

    public function marche()
    {
        return $this->belongsTo(Marche::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function historiques()
    {
        return $this->hasMany(\App\Models\AvisHistorique::class);
    }

}
