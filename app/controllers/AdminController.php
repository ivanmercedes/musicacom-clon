<?php

namespace App\Controllers;

use App\Models\Ads;
use App\Models\User;
use App\Models\Album;
use App\Models\Lyric;
use App\Models\Artist;
use App\Models\Setting;
use App\Helpers\Helpers;


class AdminController extends BaseController {
 
    public function getIndex(){
        
        Helpers::CheckAdmin();
        
        $total_artistas = Artist::count();
        $total_canciones = Lyric::where('status','=','1')->count();
        $total_albums = Album::count();
        // $canciones_inactivas = count(Song::where('actionsong_id','=','2')->get());
        $total_usuarios= User::count();

         
         return $this->view('admin/index.twig',compact('total_artistas','total_canciones','total_usuarios','total_albums'));
    }


    public function getAds(){
        
        Helpers::CheckAdmin();
        
          $ads = Ads::first();

          return $this->view('admin/ads.twig',compact('ads'));
     }

     public function postAds(){
        
        Helpers::CheckAdmin();
        
    
        $ads = Ads::find(1);
        $ads->adaptable = $_POST['adaptable'];
        $ads->horizontal = '';
        $ads->cuadro300 = $_POST['cuadro'];
        $ads->vertical300 = $_POST['300v'];
        $ads->vertical160 = '';
        $ads->ads368 = $_POST['cuadro2'];

        if($ads->save()){
           $this->setFlashMsg('success','Anuncios actualizados con exito!');
           header('location:'.Helpers::getReferer());
           exit;
        }
        $this->setFlashMsg('error','Hubo un error al actualizar vuelve a intentarlo');
        header('location:'.Helpers::getReferer());
        exit;    
     }

     public function getAjustes(){
        
        Helpers::CheckAdmin();
        
        $setting = Setting::first();

        return $this->view('admin/ajustes.twig',compact('setting'));
     }

     public function postAjustes(){
        
        Helpers::CheckAdmin();
        
        $setting = Setting::find(1);
        $setting->website_name = $_POST['website_name'];
        $setting->website_title = $_POST['website_title'];
        $setting->website_description = $_POST['website_description'];
        $setting->facebook_url = $_POST['facebook_url'];
        $setting->instagram_url = $_POST['instagram_url'];
        $setting->twitter_url = $_POST['twitter_url'];
        $setting->youtube_url = $_POST['youtube_url'];
        $setting->meta_head = $_POST['meta_head'];
        $setting->meta_footer = $_POST['meta_footer'];

        if($setting->save()){
            $this->setFlashMsg('success','Ajustes actualizados con exito!');
            header('location:'.Helpers::getReferer());
            exit;
         }
         $this->setFlashMsg('error','Hubo un error al actualizar vuelve a intentarlo');
         header('location:'.Helpers::getReferer());
         exit;   
    
     }

     public function getAjax(){
        $tipo =  $_GET['tipo'];
        if($tipo =='artista'){
             $results = Artist::query()->select('name', 'id')->where('name', 'LIKE', $_GET['q'].'%')->limit(5)->get();
             foreach ($results as $query)
             {
                 $arg[] = [ 'id' => $query->id, 'text' => $query->name];
             }
        }else if($tipo =='album'){
                $results = Album::query()->where('artist_id', $_GET['artist'])->get();
                $arg = $results; 
        }

          return json_encode($arg);
    }

 
}