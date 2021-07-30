<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lyric extends Model{

    
    
    protected $table = "lyrics";
    protected $perPage = 25;
    protected $hidden  = ['id','user_id','Status','artist_id','album_id','view','created_at','updated_at'];

    public function album(){
        return $this->belongsTo(Album::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function artist(){
        return $this->belongsTo(Artist::class);
    }


    public function rating(){
        return $this->hasMany(Rating::class);
    }

}