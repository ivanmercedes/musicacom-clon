<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model{
    protected $table = "genre";   

    public function lyric(){
        return $this->hasMany(Artist::class);
    }
}
