<?php

namespace App\Controllers;

use App\Models\Ads;
use App\Models\Genre;
use App\Models\Lyric;
use App\Models\Artist;
use App\Models\Rating;
use App\Models\Setting;
use App\Helpers\Helpers;
use App\Models\Favorite;
use Melbahja\Seo\Factory;

class IndexController extends BaseController {
 
    public function getIndex(){

         $top_lyrics =  Lyric::query()->orderBy('view','desc')->where('status','=','1')->limit(4)->get();
         $lyrics =  Lyric::query()->orderBy('id','desc')->where('status','=','1')->limit(4)->get();
         $artists = Artist::query()->orderBy('view','desc')->limit(10)->get();
         $setting = Setting::first();
         $ads = Ads::first();
         $bs = 'show';
         
         $metatags = Factory::metaTags();
  
         $metatags->meta('title', $setting->website_title .' - '. $setting->website_name)
        ->meta('description', $setting->website_description)
        ->meta('robots', 'index, follow')
        ->url(Helpers::home_url().'letras-de-canciones')
        ->facebook('locale', 'es_ES');

         $general = array(
            'title' => $setting->website_title .' - '. $setting->website_name,
            'setting' =>$setting
         );
         
         return $this->view('index.twig',compact('lyrics','top_lyrics','artists','ads','bs','general'));
    }

    public function getLetrasDeCnaciones(){
        $ads = Ads::first();
        $top_lyrics =  Lyric::query()->orderBy('view','desc')->where('status','=','1')->limit(50)->get();
        

        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Letras de Canciones Mas Populares - ' .$setting->website_name)
       ->meta('description', 'Nuestras letras de canciones más leídas y sus videos')
       ->meta('robots', 'index, follow')
       ->url(Helpers::home_url().'letras-de-canciones')
       ->facebook('locale', 'es_ES');

       
        $general = array(
           'title' => 'Letras de Canciones Mas Populares - '  .$setting->website_name,
           'setting' =>$setting,
           'metas' => $metatags
        );

        $active = 'top';
        return $this->view('letras-canciones.twig',compact('ads','top_lyrics','active','general'));
    }

    public function getLetrasNuevas(){
        $ads = Ads::first();
        $top_lyrics =  Lyric::query()->orderBy('id','desc')->where('status','=','1')->limit(50)->get();

        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Nuevas Letras de Canciones - ' .$setting->website_name)
       ->meta('description', 'Estrenos de canciones con sus letras para que las puedas ir aprendiendo..')
       ->meta('robots', 'index, follow')
       ->url(Helpers::home_url().'nueva-canciones')
       ->facebook('locale', 'es_ES');

        $general = array(
           'title' => 'Nuevas Letras de Canciones - ' .$setting->website_name,
           'setting' =>$setting,
           'metas' => $metatags
        );

        $active = 'nueva';
        return $this->view('nuevas-canciones.twig',compact('ads','top_lyrics','active','general'));
    }

    public function getLetrasBuscar(){
        $ads = Ads::first();
        $artists =  Artist::query()->orderBy('id','desc')->where('name', 'LIKE', $_GET['g'].'%')->limit(60)->get();
        $active = '';
        $q = $_GET['g'];
        

        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Los artistas que comienzan con la letra '.$q.' - ' .$setting->website_name)
       ->meta('description', 'Todos nuestros artistas que comienzan con la letra '.$q)
       ->meta('robots', 'index, follow')
       ->url(Helpers::home_url().'por-letra/'.$q)
       ->facebook('locale', 'es_ES');

        $general = array(
           'title' =>  'Los artistas que comienzan con la letra '.$q.' - ' .$setting->website_name,
           'setting' =>$setting,
           'metas' => $metatags
        );

        return $this->view('por-letra.twig',compact('ads','artists','active','q'));
    }

    public function getPorletra($id=''){
       
       $ads = Ads::first();
       $artists =  Artist::query()->orderBy('id','desc')->where('name', 'LIKE', $id.'%')->limit(60)->get();
       $top_lyrics =  Lyric::query()->orderBy('view','desc')->where('status','=','1')->limit(5)->get();
       $lyrics =  Lyric::query()->orderBy('id','desc')->where('status','=','1')->limit(4)->get();
       $active = '';
       $q = $id;

        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Los artistas que comienzan con la letra '.$q.' - ' .$setting->website_name)
       ->meta('description', 'Todos nuestros artistas que comienzan con la letra '.$q)
       ->meta('robots', 'index, follow')
       ->url(Helpers::home_url().'por-letra/'.$q)
       ->facebook('locale', 'es_ES');

        $general = array(
           'title' =>  'Los artistas que comienzan con la letra '.$q.' - ' .$setting->website_name,
           'setting' =>$setting,
           'metas' => $metatags
        );


       return $this->view('por-letra.twig',compact('ads','artists','active','q','top_lyrics','lyrics','general'));
    }

    public function getTopArtistas(){
        
         $ads = Ads::first();
         $top_artista =  Artist::query()->orderBy('view','desc')->limit(100)->get();
         $top_lyrics =  Lyric::query()->orderBy('view','desc')->where('status','=','1')->limit(5)->get();
         $lyrics =  Lyric::query()->orderBy('id','desc')->where('status','=','1')->limit(4)->get();

         $active = 'topar';
 
         $setting = Setting::first();
         $metatags = Factory::metaTags();
   
         $metatags->meta('title', 'TOP Artista - ' .$setting->website_name)
         ->meta('description', 'Ranking de los grupos y cantantes más visitados en esta semana. Lo mejor de la música hoy.')
         ->meta('robots', 'index, follow')
         ->url(Helpers::home_url().'top-artistas')
         ->facebook('locale', 'es_ES');
 
          $general = array(
            'title' =>  'TOP Artista - ' .$setting->website_name,
            'setting' =>$setting,
            'metas' => $metatags
          );

         
        return $this->view('top-artista.twig',compact('ads','active','top_artista','top_lyrics','lyrics','general'));
    }

    public function getGeneros(){
        $generos = Genre::get();
        $ads = Ads::first();
        $active = 'playlist';
 
        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'GENEROS DE MÚSICA - ' .$setting->website_name)
        ->meta('description', 'Diferentes playlist de música: lo mejor del pop, lo mejor del rock, novedades musicales...')
        ->meta('robots', 'index, follow')
        ->url(Helpers::home_url().'top-artistas')
        ->facebook('locale', 'es_ES');

         $general = array(
           'title' =>  'GENEROS DE MÚSICA  - ' .$setting->website_name,
           'setting' =>$setting,
           'metas' => $metatags
         );
         
        return $this->view('playlists.twig',compact('ads','active','general','generos'));
    }

    public function getGenero($id=''){

        $genero = Genre::where('slug',$id)->first();
        
        if(!$genero){ header('location:'.Helpers::home_url()); }

        foreach(json_decode($genero->lyric) as $item){
            $find[] = $item->id;
         }
         if(empty($find)){ return false; }
         $lyrics = Lyric::where('artist_id',$find)->orderBy('id','DESC')->limit(60)->get();
       
        $ads = Ads::first();
        $active = '';
 
        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Canciones del genero ' .$genero->name)
        ->meta('description', 'Letra y videos de las canciones más visitadas del genero '.$genero->name)
        ->meta('robots', 'index, follow')
        ->url(Helpers::home_url().'top-artistas')
        ->facebook('locale', 'es_ES');

         $general = array(
           'title' => 'Canciones del genero ' .$genero->name,
           'setting' =>$setting,
           'metas' => $metatags
         );
         
        return $this->view('genero.twig',compact('ads','active','top_artista','top_lyrics','lyrics','general','genero'));
    }

    public function getCuenta(){

        Helpers::isLogin();

        $favoritas = Favorite::where('user_id',$_SESSION['userId'])->get();
        

        $ads = Ads::first();
        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Canciones del genero ')
        ->meta('description', 'Letra y videos de las canciones más visitadas del genero ')
        ->facebook('locale', 'es_ES');

         $general = array(
           'title' => 'Canciones del genero ',
           'setting' =>$setting,
           'metas' => $metatags
         );

        return $this->view('cuenta/index.twig',compact('general','favoritas'));
    }

    public function getCuentaFavoritas(){
       return  $this->getCuenta();
    }

    public function getCuentaPlaylist(){
        Helpers::isLogin();

        $ads = Ads::first();
        $setting = Setting::first();
        $metatags = Factory::metaTags();
  
        $metatags->meta('title', 'Canciones del genero ')
        ->meta('description', 'Letra y videos de las canciones más visitadas del genero ')
        ->facebook('locale', 'es_ES');

         $general = array(
           'title' => 'Canciones del genero',
           'setting' =>$setting,
           'metas' => $metatags
         );
         return $this->view('cuenta/index.twig',compact('general'));
    }

    public function postVoto(){

        if(isset($_SERVER['HTTP_REFERER'])){

            $parts = parse_url( $_SERVER['HTTP_REFERER'] );
            $url = $parts[ "scheme" ] . "://" . $parts[ "host" ].'/';
           
            if($url == \App\Helpers\Helpers::home_url()){
             $rating = Rating::where('lyric_id',$_POST['postID'])->where('ip',Helpers::getUserIpAddr())->first();
            
             if(!$rating){
                $add_voto = new Rating();
                $add_voto->ip = Helpers::getUserIpAddr();
                $add_voto->lyric_id = $_POST['postID'];
                $add_voto->rating = $_POST['ratingNum'];
                $estado = $add_voto->save();

                $lyric = Lyric::find($_POST['postID']);
                $total_rate =  $lyric->rating->count();
                $total_sum  = $lyric->rating->sum('rating');
                $avg = round( $lyric->rating->sum('rating') / $lyric->rating->count(),2);
                $votos = compact('total_sum', 'avg','total_rate');

                echo json_encode(['status'=>1,'data'=>$votos]);
         
             }else{
                 echo 0;
             }
              
         }
      }
    }

    public function getApi(){

        if(isset($_GET['q']) && isset($_GET['t']) && isset($_SERVER['HTTP_REFERER'])){

             $parts = parse_url( $_SERVER['HTTP_REFERER'] );
             $url = $parts[ "scheme" ] . "://" . $parts[ "host" ].'/';
            
             if($url == \App\Helpers\Helpers::home_url()){
            
                switch ($_GET['t']) {
                    case 'all':
                        $search =  Lyric::query()->orderBy('id','desc')->where('title', 'LIKE', $_GET['q'].'%')->limit(20)->get();
                        $search_artista =  Artist::query()->orderBy('id','desc')->where('name', 'LIKE', $_GET['q'].'%')->limit(20)->get();
                         
                        $content = '';
                        if(count($search_artista) > 0){
                            $content .= '<ul class="list-group list-group-flush">
                            Artistas';
                            foreach($search_artista as $item){
                                $content .= '<a href="'.\App\Helpers\Helpers::home_url().'artista/'.$item->slug.'" class="list-group-item list-group-item-action">
                                <img src="'.\App\Helpers\Helpers::home_url().'upload/artista/'.$item->image.'" class="img-fluid" width="60">
                                '.$item->name.'</a>';
                            }
                            $content .= '</ul>';
                        }

                        if(count($search) > 0){
                            $content .= '<ul class="list-group list-group-flush">
                            Canciones';
                            foreach($search as $item){
                                $content .= '<a href="'.\App\Helpers\Helpers::home_url().'letra/'.$item->slug.'" class="list-group-item list-group-item-action">
                                <img src="'.\App\Helpers\Helpers::home_url().'upload/cover/'.$item->cover.'" class="img-fluid" width="60">
                                '.$item->title.'</a>';
                            }
                            $content .= '</ul>';
                        }
                        
                        if(count($search) == 0 && count($search_artista) == 0){
                            $content = '<p>No hay resultados.</p>';
                        }

                        break;
                    case 'artist':
                        $search_artista =  Artist::query()->orderBy('id','desc')->where('name', 'LIKE', '%'.$_GET['q'].'%')->limit(20)->get();
                        if(count($search_artista) > 0){
                            $content = '<ul class="list-group list-group-flush">';
                            foreach($search_artista as $item){
                                $content .= '<a href="'.\App\Helpers\Helpers::home_url().'artista/'.$item->slug.'" class="list-group-item list-group-item-action">
                                <img src="'.\App\Helpers\Helpers::home_url().'upload/artista/'.$item->image.'" class="img-fluid" width="60">
                                '.$item->name.'</a>';
                            }
                          $content .= '</ul>';
                        }
                        if(count($search_artista) == 0){
                            $content = '<p>No hay resultados.</p>';
                        }
                     break;
                     case 'song':
                        $search =  Lyric::query()->orderBy('id','desc')->where('title', 'LIKE', '%'.$_GET['q'].'%')->limit(20)->get();
                        if(count($search) > 0){
                            $content = '<ul class="list-group list-group-flush">';
                            foreach($search as $item){
                                $content .= '<a href="'.\App\Helpers\Helpers::home_url().'letra/'.$item->slug.'" class="list-group-item list-group-item-action">
                                <img src="'.\App\Helpers\Helpers::home_url().'upload/cover/'.$item->cover.'" class="img-fluid" width="60">
                                '.$item->title.'</a>';
                            }
                          $content .= '</ul>';
                        }

                        if(count($search) == 0){
                            $content = '<p>No hay resultados.</p>';
                        }
                    break;
                    default:
                       
                     break;
                }

                echo $content;
             }  
        }
     } 
     
    
}