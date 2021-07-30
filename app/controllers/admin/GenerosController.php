<?php

namespace App\Controllers\Admin;

use App\Models\Genre;
use App\Helpers\Helpers;
use Cocur\Slugify\Slugify;
use App\Controllers\BaseController;



class GenerosController extends BaseController {


    public function getIndex(){
        $genres = Genre::orderBy('id','desc')->get();
        return $this->view('admin/generos/generos.twig',compact('genres'));
    }

    public function getAdd(){
        $genres = Genre::orderBy('id','desc')->get();
        return $this->view('admin/generos/add.twig',compact('genres'));
    }

    public function postAdd(){


         Helpers::CheckAdmin();

         $slugify = new Slugify();
 
         $genero = new Genre();
         $genero->name = $_POST['nombre'];
         $genero->description = $_POST['descripcion'];
         $genero->slug = $slugify->slugify($_POST['nombre']);

         $genero->save();

        $this->setFlashMsg('success','Genero agregado correctamente');
        header('location:'. \App\Helpers\Helpers::home_url().'admin/generos');
        exit;
        
    }

    
    public function getDelete($id){

        Helpers::CheckAdmin();
        $del= Genre::find($id);
        $del->delete();

        $this->setFlashMsg('success','Genero eliminado correctamente');
        header('location:'.\App\Helpers\Helpers::home_url().'admin/generos');
        exit;
    }

    public function getEdit($id){
       $genre = Genre::find($id);
       return $this->view('admin/generos/add.twig',compact('genre'));
    }

    public function postEdit($id){

        Helpers::CheckAdmin();
        $slugify = new Slugify();
        
        $genre = Genre::find($id);
        $genre->name = $_POST['nombre'];
        $genre->description = $_POST['descripcion'];
        $genre->slug = $slugify->slugify($_POST['nombre']);

        $genre->save();
        $this->setFlashMsg('success','Genero actualizado correctamente');
        header('location:'. \App\Helpers\Helpers::home_url().'admin/generos');
        exit;
    }
        

}