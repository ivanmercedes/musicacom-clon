<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {
    protected $tale = "favorites";
    public $timestamps = false;
    
    public function lyric(){
        return $this->belongsTo(Lyric::class);
    }
}