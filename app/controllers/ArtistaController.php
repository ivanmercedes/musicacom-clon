<?php

namespace App\Controllers;

use App\Models\Ads;
use App\Models\Lyric;
use App\Models\Artist;
use App\Models\Setting;
use App\Helpers\Helpers;
use Melbahja\Seo\Factory;

class ArtistaController extends BaseController {
 
    public function getIndex($id = ''){

         $lyric =  Artist::query()->where('slug',  '=', $id)->first();
         if(!$lyric){  header('location:'.Helpers::home_url());  exit;}
         $lyric->view = $lyric->view+1;
         $setting = Setting::first();
         $lyric->save();
         $ads = Ads::first();
         
         $metatags = Factory::metaTags();
   
         $metatags->meta('title', 'Letra de '.$lyric->name.' - ' .$setting->website_name)
        ->meta('description', 'Letras, fotos y videos de '.$lyric->nam)
        ->meta('robots', 'index, follow')
		->image(Helpers::home_url().'upload/artista/'.$lyric->image)
        ->url(Helpers::home_url().'artista/'.$lyric->slug)
        ->facebook('locale', 'es_ES')
        ->facebook('type', 'music.song');

        
         $general = array(
            'title' => 'Letra de '.$lyric->name.' - ' .$setting->website_name,
            'setting' =>$setting,
            'metas' => $metatags
         );


         return $this->view('artista.twig',compact('lyric','ads','general'));
        
    }
    
}