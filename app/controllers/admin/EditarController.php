<?php

namespace App\Controllers\Admin;
use App\Models\Lyric;
use App\Helpers\Helpers;
use Cocur\Slugify\Slugify;
use App\Controllers\BaseController;

class EditarController extends BaseController {
 
    public function getIndex($id_lyric=''){
        Helpers::CheckAdmin();
        $lyric = Lyric::find($id_lyric);
        return $this->view('admin/canciones/editar.twig',compact('lyric'));
    }

    public function postIndex($id_lyric){

        
        Helpers::CheckAdmin();
        $slugify = new Slugify();
        $lyric = Lyric::find($id_lyric);
        $lyric->title = $_POST['titulo'];
        $lyric->slug = $slugify->slugify($_POST['titulo']);
        $lyric->video = $_POST['video'];
        $lyric->feats = (!empty($_POST['feat']))? serialize($_POST['feat']) : '';
        $lyric->description = $_POST['description'];
        $letra = nl2br($_POST['letra'], false);
        $letra = '<p>' . preg_replace('#(<br>[\r\n]+){2}#', "</p>\n\n<p>", $letra) . '</p>';
        $lyric->lyric = $letra;
        $lyric->Status = $_POST['estado'];
        $lyric->user_id = $_SESSION['userId'];
        $lyric->artist_id = $_POST['artista'];
        $lyric->album_id = ($_POST['album'])? $_POST['album']: 0;
        $lyric->view = 0;

        if (isset($_FILES['file']) && !$_FILES['file']['size'] == 0) {

            if(file_exists('upload/cover/'.$lyric->cover)){
                 unlink('upload/cover/'.$lyric->cover); 
             }
                 $image = $this->upload($_FILES['file']); 
                 $lyric->cover = $image;
          
        }

        $estado = $lyric->save();

        if($estado){
            $this->setFlashMsg('success','Canción actualizada correctamente');
            header('location:'. \App\Helpers\Helpers::home_url().'admin/canciones');
            exit;
        } 
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
          $handle->process('upload/cover');
          if ($handle->processed) {
            return $handle->file_dst_name;
            $handle->clean();
             } else {
               return false;
            }
          } // if handle
    } 
    
}