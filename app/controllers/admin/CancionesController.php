<?php

namespace App\Controllers\Admin;
use App\Models\Lyric;
use App\Helpers\Helpers;
use App\Controllers\BaseController;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Plasticbrain\FlashMessages\FlashMessages;
class CancionesController extends BaseController {
 
    public function getIndex(){
        
        Helpers::CheckAdmin();
        $pages= (isset($_GET['page'])? $_GET['page'] : 1);

        Paginator::currentPageResolver(function () use ($pages) {
            return $pages;
        });
         
         $canciones = Lyric::orderBy('id','desc')->where('status','=','1')->paginate();
         $paginate = Helpers::paginate($canciones);

         return $this->view('admin/canciones/index.twig', compact('canciones','paginate'));
    }
  
    public function delete($id){
        Helpers::CheckAdmin();
         $del= Lyric::find($id);
         if($del){ if(file_exists('upload/cover/'.$del->cover)){ unlink('upload/cover/'.$del->cover); }
            $del->delete();
            $this->setFlashMsg('success','Canción Eliminada correctamente');
            header('location:'.Helpers::getReferer());
          exit;
        } 
        $this->setFlashMsg('error','Error al Eliminar Canción');
        header('location:'.Helpers::getReferer());
        exit;         
    }
}