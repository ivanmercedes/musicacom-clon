<?php

namespace App\Controllers;

use App\Models\Lists;
use App\Models\Lyric;
use App\Models\Artist;
use App\Models\Setting;
use App\Helpers\Helpers;
use Plasticbrain\FlashMessages\FlashMessages;



class BaseController extends Helpers {

    protected $templateEngine;
    public $cities;

    public function __construct(){
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->templateEngine = new \Twig\Environment($loader,[
            'debug' => true,
            'cache' => false
            // 'cache' => '../temp'
        ]);

        $filter = new \Twig\TwigFilter('url', function ($path) {
            return $this->home_url().$path;
        });

        $twigFunction = new \Twig\TwigFilter('msg', function () {
            $msg = new FlashMessages();
            // $msg->display();
            return $msg;
        });
        
      

        $this->templateEngine->addFunction(
            new \Twig\TwigFunction(
                'getChecked',
                function($id, $song) {

                    $results = Lists::where('playlist_id', $id)->where('lyric_id', $song)->first();
                    return $results;
                }
            )
        );
        $this->templateEngine->addFunction(
            new \Twig\TwigFunction(
                'getTotal',
                function($ids) {
                  
                    foreach(json_decode($ids) as $item){
                       $find[] = $item->id;
                    }
                    if(empty($find)){ return false; }
                    $results = Lyric::where('artist_id',$find)->count();
                    return $results;
                }
            )
        );

        $this->templateEngine->addFunction(
            new \Twig\TwigFunction(
                'getFeats',
                function($base,$feats) {
                    $feats = unserialize($feats);
                    $results['author'] = Artist::query()->select('name', 'slug')->find($base);
                    $results['feats']  = Artist::query()->select('name', 'slug')->find($feats);
                    return $results;
                }
            )
        );

        $this->templateEngine->addFunction(
            new \Twig\TwigFunction(
                'getArtistsFromID',
                function($ids,$unserial = false) {
                    $ids = (!$unserial)? unserialize($ids) : $ids;
                    $results = Artist::query()->select('name', 'id')->find($ids);
                    return $results;
                }
            )
        );

        $this->templateEngine->addFilter($twigFunction);
        $this->templateEngine->addFilter($filter);
        $this->templateEngine ->addExtension(new \Twig\Extension\DebugExtension());   
       
    }

    public function view($fileName, $data = []){
        if(isset($_SESSION['userId'])){
          $data['estaLogin'] =  true;
          $data['userName'] =  $_SESSION['name'];
        }
        return $this->templateEngine->render($fileName,$data);
    }
   /**
    * @method success, info,warning,error
    * @param Method,Array
    */
    public function setFlashMsg($type ='', $text=[]){
        $msg = new FlashMessages();
        $initial_array = [$text];
        $final_array = array_map([$msg, $type], $initial_array);
        return  $msg;
    }
}