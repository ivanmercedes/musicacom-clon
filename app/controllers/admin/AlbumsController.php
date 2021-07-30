<?php

namespace App\Controllers\Admin;

use App\Models\Album;
use App\Helpers\Helpers;
use Cocur\Slugify\Slugify;
use App\Controllers\BaseController;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;



class AlbumsController extends BaseController {


    public function getIndex(){

        $pages= (isset($_GET['page'])? $_GET['page'] : 1);

        Paginator::currentPageResolver(function () use ($pages) {
            return $pages;
        });

        $albums = Album::orderBy('id','desc')->paginate();
        $paginate = Helpers::paginate($albums);
        return $this->view('admin/albums/albums.twig',compact('albums','paginate'));
    }

    public function getAdd( ){
        return $this->view('admin/albums/add.twig');
    }

    public function postAdd(){

         Helpers::CheckAdmin();

         $slugify = new Slugify();
         $image = $this->upload($_FILES['imagen']); 
         $Album = new album();
         $Album->name_album = $_POST['nombre'];
         $Album->slug = $slugify->slugify($_POST['nombre']);
         $Album->description = $_POST['descripcion'];
         $Album->year = $_POST['year'];
         $Album->image = $image;
         $Album->artist_id = $_POST['artista'];

         $Album->save();

         $this->setFlashMsg('success','Album agregado correctamente');
         header('location:'. \App\Helpers\Helpers::home_url().'admin/albums');
         exit;  
    }

    
    public function getDelete($id){

        Helpers::CheckAdmin();
        $del= Album::find($id);

        if($del){ 
          if(file_exists('upload/album/'.$del->image)){ unlink('upload/album/'.$del->image); }
           $del->delete();
           $this->setFlashMsg('success','Album Eliminado correctamente');
           header('location:'.Helpers::getReferer());
           exit;
         } 
        $this->setFlashMsg('error','Error al Eliminar Album');
        header('location:'.Helpers::getReferer());
       exit;  
    }

    public function getEdit($id){
       $album = Album::find($id);
       return $this->view('admin/albums/add.twig',compact('album'));
    }

    public function postEdit($id){

        Helpers::CheckAdmin();
        $slugify = new Slugify();
        
        $Album = Album::find($id);

        if (isset($_FILES['imagen']) && !$_FILES['imagen']['size'] == 0) {
            
            if(file_exists('upload/album/'.$Album->image)){
                 unlink('upload/album/'.$Album->image); 
            }
                 $image = $this->upload($_FILES['imagen']); 
                 $Album->image = $image;
           
        }

        
        $Album->name_album = $_POST['nombre'];
        $Album->slug = $slugify->slugify($_POST['nombre']);
        $Album->description = $_POST['descripcion'];
        $Album->year = $_POST['year'];
       
        $Album->artist_id = $_POST['artista'];

        $Album->save();

        $this->setFlashMsg('success','Album actualizado correctamente');
        header('location:'. \App\Helpers\Helpers::home_url().'admin/albums');
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
          $handle->process('upload/album');
          if ($handle->processed) {
            return $handle->file_dst_name;
            $handle->clean();
             } else {
               return false;
            }
          } // if handle
    } 
}