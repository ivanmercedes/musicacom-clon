<?php

namespace App\Controllers;

use App\Models\Ads;
use App\Models\User;
use App\Models\Lists;
use App\Models\Lyric;
use App\Models\Setting;
use App\Helpers\Helpers;
use App\Models\Favorite;
use App\Models\Playlist;
use Melbahja\Seo\Factory;

class LetraController extends BaseController {
 
    public function getIndex($id = ''){

         $lyric =  Lyric::query()->where('slug',  '=', $id)->first();
         if(!$lyric){  header('location:'.Helpers::home_url());  exit;}
         $lyric->view = $lyric->view+1;
         $lyric->save();
         $ads =  Ads::first();
         $ram = Lyric::inRandomOrder()->select('slug')->first();
         $setting = Setting::first();
         $ads = Ads::first();
         $bs = 'show';

         $user_id = (isset($_SESSION['userId']))? $_SESSION['userId'] : null;
         $playlist = User::find($user_id);

         $metatags = Factory::metaTags();
         $ft = Helpers::getFeatSeo($lyric->feats);
         $metatags->meta('title', 'Letra de '.$lyric->title.', '. $lyric->artist->name.$ft.' - Lyric')
        ->meta('description', 'Letra y video de '.$lyric->title.', '. $lyric->artist->name.$ft)
        ->meta('robots', 'index, follow')
		->image(Helpers::home_url().'upload/cover/'.$lyric->cover)
        ->url(Helpers::home_url().'letra/'.$lyric->slug)
        ->facebook('locale', 'es_ES')
        ->facebook('type', 'music.song');

        
         $general = array(
            'title' => 'Letra de '.$lyric->title.', '. $lyric->artist->name.$ft.' - Lyric',
            'setting' =>$setting,
            'metas' => $metatags
         );

        $total_rate =  $lyric->rating->count();

         if($total_rate != 0){
            $total_sum  = $total_rate;
            $avg = round( $lyric->rating->sum('rating') / $lyric->rating->count(),2);
            $votos = compact('total_sum', 'avg');
         }else{
            $total_sum  =0;
            $avg = 0.0;
            $votos = compact('total_sum', 'avg');
         }

        return $this->view('letra.twig',compact('lyric','ads','general','votos','ram','playlist'));
    }

    public function postApi(){

        
        if(isset($_SERVER['HTTP_REFERER'])){

            $parts = parse_url( $_SERVER['HTTP_REFERER'] );
            $url = $parts[ "scheme" ] . "://" . $parts[ "host" ].'/';
           
            if($url == \App\Helpers\Helpers::home_url() && isset($_POST['id'])){

                  if(isset($_SESSION['userId'])){
                     $fav = new Favorite();
                     $check = $fav->where('user_id',$_SESSION['userId'])->where('lyric_id',$_POST['id'])->first();
                     if(!$check ){
                        $fav->user_id = $_SESSION['userId'];
                        $fav->lyric_id = $_POST['id'];
                        $fav->save();
                        return 1;
                     }
                     return 2;
                  }else{
                      echo 3;
                  }
            }

        }
    }

    public function postList(){
  
        if(isset($_SERVER['HTTP_REFERER'])){

            $parts = parse_url( $_SERVER['HTTP_REFERER'] );
            $url = $parts[ "scheme" ] . "://" . $parts[ "host" ].'/';
           
            if($url == \App\Helpers\Helpers::home_url() && isset($_POST['id']) && $_POST['tipo'] == 'new_playlist'){

                  if(isset($_SESSION['userId'])){
                     $playlist = new Playlist();
                     $check = $playlist->where('user_id',$_SESSION['userId'])->where('name',$_POST['nombre'])->first();
                     if(!$check){
                        $playlist->name = $_POST['nombre'];
                        $playlist->user_id = $_SESSION['userId'];
                        $playlist->save();
                        return  $playlist;
                     }
                     return json_encode(['error'=>'Esa lista ya esxiste']);
                  }else{
                      return 3;
                }
            }else if($url == \App\Helpers\Helpers::home_url() && isset($_POST['id']) && $_POST['tipo'] == 'addToPlaylist'){
                    $list = new Lists();

                    $list->playlist_id = $_POST['id'];
                    $list->lyric_id = $_POST['id_song'];
                    $list->save();
                    return 1;
            }else if($url == \App\Helpers\Helpers::home_url() && isset($_POST['id']) && $_POST['tipo'] == 'removeToPlaylist'){

                $list = new Lists();
                $list = $list::where('lyric_id',$_POST['id_song'])->where('playlist_id',$_POST['id']);
                $list->delete();
                return 2;
            }


        }
    }
    
}
