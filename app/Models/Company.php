<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'entreprises';
    protected $fillable = ['nom'];

    public function factures()
    {
        return $this->hasMany(Invoice::class, 'entreprise_id');
    }
}
