<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'factures';
    protected $fillable = [
        'entreprise_id',
        'type',
        'tiers',
        'numero_facture',
        'date_facture',
        'montant',
        'statut',
        'description',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Company::class, 'entreprise_id');
    }

    public function pieces_jointes()
    {
        return $this->hasMany(Attachment::class, 'facture_id');
    }
}
