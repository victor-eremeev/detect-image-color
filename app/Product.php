<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Mass assigned
    protected $guarded = [];
    
    public function downloads()
    {
        return $this->morphToMany(Download::class, 'downloadable')->orderBy('sort', 'asc');
    }
}
