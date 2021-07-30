<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model{
    
    protected $table = "artists";
    protected $perPage = 20;

    public function lyric(){
        return $this->hasMany(Lyric::class)->orderBy('id','DESC');
    }
}
