<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'pieces_jointes';
    protected $fillable = ['facture_id', 'chemin_fichier', 'type_fichier'];

    public function facture()
    {
        return $this->belongsTo(Invoice::class, 'facture_id');
    }
}
