<?php

namespace App\Controllers\Admin;

use App\Models\Genre;
use App\Models\Artist;
use App\Helpers\Helpers;
use Cocur\Slugify\Slugify;
use App\Controllers\BaseController;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


class ArtistController extends BaseController {


    public function getIndex(){

        $pages= (isset($_GET['page'])? $_GET['page'] : 1);

        Paginator::currentPageResolver(function () use ($pages) {
            return $pages;
        });

        $artists = Artist::orderBy('id','desc')->paginate();
        $paginate = Helpers::paginate($artists);
        return $this->view('admin/artistas/artistas.twig',compact('artists','paginate'));
    }

    public function getAdd(){
        $generos =  Genre::orderBy('id','desc')->get();
        return $this->view('admin/artistas/add.twig',compact('generos'));
    }

    public function postAdd(){

         Helpers::CheckAdmin();

         $slugify = new Slugify();
         $image = $this->upload($_FILES['imagen']); 
         $artist = new Artist();
         $artist->name = $_POST['nombre'];
         $artist->slug = $slugify->slugify($_POST['nombre']);
         $artist->bio = $_POST['bios'];
         $artist->genre_id = $_POST['genero'];
         $artist->image = $image;
   

         $artist->save();

         $this->setFlashMsg('success','Artista agregado correctamente');
         header('location:'. \App\Helpers\Helpers::home_url().'admin/artistas');
         exit;  
    }

    
    public function getDelete($id){

        Helpers::CheckAdmin();
        $del= Artist::find($id);

        if($del){ 
          if(file_exists('upload/artista/'.$del->image)){ unlink('upload/artista/'.$del->image); }
           $del->delete();
           $this->setFlashMsg('success','Album Eliminado correctamente');
           header('location:'.Helpers::getReferer());
           exit;
         } 
        $this->setFlashMsg('error','Error al Eliminar el Artista');
        header('location:'.Helpers::getReferer());
       exit;  
    }

    public function getEdit($id){
       $artista =  Artist::find($id);
       $generos =  Genre::orderBy('id','desc')->get();
       return $this->view('admin/artistas/add.twig',compact('artista','generos'));
    }

    public function postEdit($id){

        Helpers::CheckAdmin();
        $slugify = new Slugify();
        
        $artista = Artist::find($id);

        if (isset($_FILES['imagen']) && !$_FILES['imagen']['size'] == 0) {

            if(file_exists('upload/artista/'.$artista->image)){
                 unlink('upload/artista/'.$artista->image); 
             }
                 $image = $this->upload($_FILES['imagen']); 
                 $artista->image = $image;
          
        }
        
        $artist->name = $_POST['nombre'];
        $artist->slug = $slugify->slugify($_POST['nombre']);
        $artist->bio = $_POST['bios'];
        $artist->genre_id = $_POST['genero'];

    

        $artista->save();

        $this->setFlashMsg('success','Artista actualizado correctamente');
        header('location:'. \App\Helpers\Helpers::home_url().'admin/artistas');
        exit;
    }
    
    public function upload($file){

        if(empty($file)){  return false;  }

        $handle = new \Verot\Upload\Upload($file);
        if ($handle->uploaded) {
         
          $handle->file_new_name_body   = substr(uniqid(mt_rand()),0,10);
          $handle->jpeg_quality         = 90;
          $handle->allowed              = array( 'image/*');
          $handle->image_convert        = 'jpg';
          $handle->file_name_body_pre   = 'thumb_';
          $handle->image_resize         = true;
          $handle->image_x              = 200;
          $handle->image_y              = 200;
          $handle->image_ratio_y        = false;
          $handle->image_ratio_crop     = true;
          $handle->process('upload/artista');
          if ($handle->processed) {
            return $handle->file_dst_name;
            $handle->clean();
             } else {
               return false;
            }
          } // if handle
    } 
}