<?php

namespace App\Controllers\Admin;
use App\Models\Lyric;
use App\Helpers\Helpers;
use App\Controllers\BaseController;
use Plasticbrain\FlashMessages\FlashMessages;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


class PendientesController extends BaseController {
 
    public function getIndex(){
         Helpers::CheckAdmin();
        

           
        Helpers::CheckAdmin();
        $pages= (isset($_GET['page'])? $_GET['page'] : 1);

        Paginator::currentPageResolver(function () use ($pages) {
            return $pages;
        });
         
         $canciones = Lyric::orderBy('id','desc')->where('status','=','0')->paginate();
         $paginate = Helpers::paginate($canciones);

        // return $this->view('admin/canciones/index.twig', compact('canciones','paginate'));
         return $this->view('admin/canciones/pendientes.twig',compact('canciones','paginate'));
    }

    public function postIndex(){
        return 'This will respond to /controller/test with only a POST method';
    }
    
}