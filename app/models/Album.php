<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Album extends Model {
    
    protected $tale = "albums";
    protected $perPage = 25;
    
    public function lyric(){
        return $this->hasMany(Lyric::class);
    }

    public function artist(){
        return $this->belongsTo(Artist::class);
    }
}