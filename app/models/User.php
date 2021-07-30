<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $table = "users";
    protected $perPage = 25;
    // protected $visible = ['first_name', 'last_name'];
    //  protected $visible = ['id','name','email','email_verified_at'];

    public function playlist(){
        return $this->hasMany(Playlist::class);
    }
   
}